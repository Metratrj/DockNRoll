<?php

namespace App\Services;

use GuzzleHttp\Client;
use OpenAPI\Client\Api\ContainerApi;
use OpenAPI\Client\Api\ImageApi;
use OpenAPI\Client\Configuration;
use OpenAPI\Client\ObjectSerializer;
use Predis\Client as RedisClient;

class CrawlerService
{
    private RedisClient $redis;
    private ContainerApi $containerApi;
    private ImageApi $imageApi;
    private const INDEX_NAME = 'docknroll_idx';

    public function __construct(string $host = "http://localhost:2375")
    {
        $this->redis = new RedisClient([
            'scheme' => 'tcp',
            'host'   => 'localhost',
            'port'   => 6379,
        ]);

        $guzzle = new Client(["base_uri" => $host, "timeout" => 30]);
        $config = Configuration::getDefaultConfiguration()->setHost($host);
        $this->containerApi = new ContainerApi($guzzle, $config);
        $this->imageApi = new ImageApi($guzzle, $config);
    }

    private function createIndex(): void
    {
        try {
            $this->redis->executeRaw(['FT.DROPINDEX', self::INDEX_NAME, 'DD']);
            echo "Dropped existing index." . PHP_EOL;
        } catch (\Exception $e) {
            // Index probably didn't exist
        }

        echo "Creating RediSearch index..." . PHP_EOL;
        $this->redis->executeRaw([
            'FT.CREATE',
            self::INDEX_NAME,
            'ON', 'JSON',
            'PREFIX', 1, 'item:',
            'SCHEMA',
            '$.id', 'AS', 'id', 'TEXT',
            '$.type', 'AS', 'type', 'TAG',
            '$.name', 'AS', 'name', 'TEXT', 'WEIGHT', '5.0',
            '$.repoTags[*]', 'AS', 'repoTags', 'TEXT', 'WEIGHT', '2.0',
            '$.fulltext', 'AS', 'fulltext', 'TEXT'
        ]);
    }

    public function crawl(): void
    {
        echo "Flushing Redis DB..." . PHP_EOL;
        $this->redis->flushdb();
        $this->createIndex();

        // Crawl Containers
        echo "Crawling Docker containers..." . PHP_EOL;
        $containers = $this->containerApi->containerList(true);
        foreach ($containers as $container) {
            $key = "item:" . $container->getId();
            $data = (array)ObjectSerializer::sanitizeForSerialization($container);
            $fulltext = json_encode($data);
            $doc = [
                'id' => $container->getId(),
                'type' => 'Container',
                'name' => ltrim($container->getNames()[0] ?? '', '/'),
                'fulltext' => $fulltext,
                'full_object' => $data
            ];
            $this->redis->executeRaw(['JSON.SET', $key, '$', json_encode($doc)]);
        }

        // Crawl Images
        echo "Crawling Docker images..." . PHP_EOL;
        $images = $this->imageApi->imageList();
        foreach ($images as $image) {
            $key = "item:" . str_replace('sha256:', '', $image->getId());
            $data = (array)ObjectSerializer::sanitizeForSerialization($image);
            $fulltext = json_encode($data);
            $doc = [
                'id' => $image->getId(),
                'type' => 'Image',
                'repoTags' => $image->getRepoTags(),
                'fulltext' => $fulltext,
                'full_object' => $data
            ];
            $this->redis->executeRaw(['JSON.SET', $key, '$', json_encode($doc)]);
        }

        echo "Done." . PHP_EOL;
    }
}