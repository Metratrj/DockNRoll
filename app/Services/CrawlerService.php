<?php

namespace App\Services;

use Exception;
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
    private const INDEX_NAME = "docknroll_idx";

    public function __construct(string $host = "http://localhost:2375")
    {
        $this->redis = new RedisClient([
            "scheme" => "tcp",
            "host" => "localhost",
            "port" => 6379,
        ]);

        $guzzle = new Client(["base_uri" => $host, "timeout" => 30]);
        $config = Configuration::getDefaultConfiguration()->setHost($host);
        $this->containerApi = new ContainerApi($guzzle, $config);
        $this->imageApi = new ImageApi($guzzle, $config);
    }

    private function createIndex(): void
    {
        try {
            $this->redis->executeRaw(["FT.DROPINDEX", self::INDEX_NAME, "DD"]);
            echo "Dropped existing index." . PHP_EOL;
        } catch (\Exception $e) {
            // Index probably didn't exist
        }

        echo "Creating RediSearch index..." . PHP_EOL;
        $this->redis->executeRaw([
            "FT.CREATE",
            self::INDEX_NAME,
            "ON",
            "JSON",
            "PREFIX",
            1,
            "item:",
            "SCHEMA",
            '$.id',
            "AS",
            "id",
            "TEXT",
            '$.type',
            "AS",
            "type",
            "TAG",
            '$.nametag',
            "AS",
            "nametag",
            "TAG",
            '$.name',
            "AS",
            "name",
            "TEXT",
            "WEIGHT",
            "5.0",
            '$.repoTags[*]',
            "AS",
            "repoTags",
            "TEXT",
            "WEIGHT",
            "2.0",
            '$.fulltext',
            "AS",
            "fulltext",
            "TEXT",
            "WITHSUFFIXTRIE",
            '$.inspect',
            "AS",
            "inspect",
            "TEXT",
            "WITHSUFFIXTRIE",
        ]);
    }

    public function crawl(): void
    {
        echo "Flushing Redis DB..." . PHP_EOL;
        $this->redis->flushdb();
        $this->createIndex();

        // Crawl Containers
        echo "Crawling Docker containers..." . PHP_EOL;
        try {
            $containers = $this->containerApi->containerList(true);
            foreach ($containers as $container) {
                $containerFull = $this->containerApi->containerInspect($container->getId());
                $key = "item:" . $container->getId();
                $data = (array) $container->jsonSerialize();
                $fulltext = json_encode($data);
                $inspect = $containerFull->__toString();
                $doc = [
                    "id" => $container->getId(),
                    "type" => "Container",
                    "name" => ltrim($container->getNames()[0] ?? "", "/"),
                    "nametag" => ltrim($container->getNames()[0] ?? "", "/"),
                    "fulltext" => $fulltext,
                    "full_object" => $data,
                    "inspect" => $inspect,
                ];
                $this->redis->executeRaw(["JSON.SET", $key, '$', json_encode($doc)]);
            }
        } catch (Exception $e) {
            echo "Exception when calling ContainerApi->containerList: ", $e->getMessage(), PHP_EOL;
        }

        // Crawl Images
        echo "Crawling Docker images..." . PHP_EOL;

        try {
            $images = $this->imageApi->imageList();
            foreach ($images as $image) {
                $key = "item:" . str_replace("sha256:", "", $image->getId());
                $data = (array) $image->jsonSerialize();
                $fulltext = json_encode($data);
                $doc = [
                    "id" => $image->getId(),
                    "type" => "Image",
                    "repoTags" => implode(", ", $image->getRepoTags()),
                    "fulltext" => $fulltext,
                    "full_object" => $data,
                ];
                $this->redis->executeRaw(["JSON.SET", $key, '$', json_encode($doc)]);
            }
        } catch (Exception $e) {
            echo "Exception when calling ImageApi->imageList: ", $e->getMessage(), PHP_EOL;
        }

        echo "Done." . PHP_EOL;
    }
}
