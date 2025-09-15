<?php

namespace App\Services;

use GuzzleHttp\Client;
use OpenAPI\Client\Api\ContainerApi;
use OpenAPI\Client\Api\ImageApi;
use OpenAPI\Client\Configuration;
use Predis\Client as RedisClient;

class CrawlerService
{
    private RedisClient $redis;
    private ContainerApi $containerApi;
    private ImageApi $imageApi;

    public function __construct(string $host = "http://localhost:2375")
    {
        // Init Redis Client
        $this->redis = new RedisClient([
            'scheme' => 'tcp',
            'host'   => 'localhost',
            'port'   => 6379,
        ]);

        // Init Docker API Clients
        $guzzle = new Client(["base_uri" => $host, "timeout" => 30]);
        $config = Configuration::getDefaultConfiguration()->setHost($host);
        $this->containerApi = new ContainerApi($guzzle, $config);
        $this->imageApi = new ImageApi($guzzle, $config);
    }

    public function crawl(): void
    {
        echo "Flushing Redis DB..." . PHP_EOL;
        $this->redis->flushdb();

        // Crawl Containers
        echo "Crawling Docker containers..." . PHP_EOL;
        $containers = $this->containerApi->containerList(true);
        foreach ($containers as $container) {
            $containerId = $container->getId();
            $key = "container:" . $containerId;

            $this->redis->hmset($key, [
                "id" => $containerId,
                "name" => $container->getNames()[0] ?? 'N/A',
                "image" => $container->getImage(),
                "state" => $container->getState(),
                "status" => $container->getStatus(),
            ]);

            $this->redis->sadd("index:type:container", [$key]);
            $this->indexString($container->getNames()[0] ?? '', $key);
        }

        // Crawl Images
        echo "Crawling Docker images..." . PHP_EOL;
        $images = $this->imageApi->imageList();
        foreach ($images as $image) {
            $imageId = $image->getId();
            $key = "image:" . str_replace('sha256:', '', $imageId);

            $this->redis->hmset($key, [
                "id" => $imageId,
                "repoTags" => implode(', ', $image->getRepoTags() ?? []),
                "size" => $image->getSize(),
            ]);

            $this->redis->sadd("index:type:image", [$key]);
            if ($image->getRepoTags()) {
                foreach ($image->getRepoTags() as $tag) {
                    $this->indexString($tag, $key);
                }
            }
        }

        echo "Done." . PHP_EOL;
    }

    private function indexString(string $string, string $itemKey): void
    {
        // Normalize the string
        $string = ltrim($string, '/');
        $string = strtolower($string);

        // Tokenize the string by common separators for Docker names
        $tokens = preg_split('/[_.-:]+/', $string);
        if (!$tokens) {
            return;
        }

        foreach ($tokens as $token) {
            $token = trim($token);
            if (strlen($token) > 1) { // Only index substrings of length 2 or more
                // Index all substrings (n-grams) to allow for infix searching
                for ($i = 0; $i < strlen($token); $i++) {
                    for ($j = 2; $j <= strlen($token) - $i; $j++) {
                        $substring = substr($token, $i, $j);
                        $indexKey = "index:term:" . $substring;
                        $this->redis->sadd($indexKey, [$itemKey]);
                    }
                }
            }
            // Always index the full token
            if (!empty($token)) {
                 $this->redis->sadd("index:term:" . $token, [$itemKey]);
            }
        }
    }
}
