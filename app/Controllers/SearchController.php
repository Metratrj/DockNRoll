<?php

namespace App\Controllers;

use App\Http\Response;
use Predis\Client as RedisClient;

class SearchController
{
    private RedisClient $redis;
    private const INDEX_NAME = 'docknroll_idx';

    public function __construct()
    {
        $this->redis = new RedisClient([
            'scheme' => 'tcp',
            'host'   => 'localhost',
            'port'   => 6379,
        ]);
    }

    public function search(): void
    {
        try {
            $requestBody = json_decode(file_get_contents('php://input'), true);
            $query = strtolower(trim($requestBody['query'] ?? ''));
            $typeFilter = $requestBody['type'] ?? null;

            $queryString = '';

            if (empty($query)) {
                $queryString = '*';
            } else {
                $queryString = "@fulltext:{$query}* | @name|repoTags|id:{$query}*";
            }

            if ($typeFilter) {
                $queryString = trim("({$queryString}) @type:{".strtolower($typeFilter)."}");
            }

            $rawResult = $this->redis->executeRaw([
                'FT.SEARCH',
                self::INDEX_NAME,
                $queryString,
                'RETURN', 1, '$'
            ]);

            $results = [
                'Container' => [],
                'Image' => [],
            ];

            $count = $rawResult[0];
            for ($i = 1; $i < count($rawResult); $i += 2) {
                $docJson = $rawResult[$i + 1][1];
                $doc = json_decode($docJson, true);
                $fullObject = $doc['full_object'];
                $type = $doc['type'];

                if ($type === 'Container') {
                    $formattedItem = [
                        'id' => $fullObject['Id'],
                        'name' => ltrim($fullObject['Names'][0] ?? '', '/'),
                        'image' => $fullObject['Image'],
                        'state' => $fullObject['State'],
                        'status' => $fullObject['Status'],
                        'type' => 'Container',
                        'actions' => $this->getContainerActions($fullObject['State'] ?? '')
                    ];
                    $results['Container'][] = $formattedItem;
                } elseif ($type === 'Image') {
                    $formattedItem = [
                        'id' => $fullObject['Id'],
                        'repoTags' => implode(', ', $fullObject['RepoTags'] ?? []),
                        'size' => $fullObject['Size'],
                        'type' => 'Image',
                        'actions' => []
                    ];
                    $results['Image'][] = $formattedItem;
                }
            }

            $finalResults = array_filter($results, fn($group) => !empty($group));

            (new Response())->json($finalResults);

        } catch (\Exception $e) {
            (new Response())->setStatus(500)->json([
                'error' => 'Failed to connect or query the search index.',
                'message' => $e->getMessage()
            ]);
        }
    }

    private function getContainerActions(string $state): array
    {
        if ($state === 'running') {
            return ['stop'];
        }
        if ($state === 'exited' || $state === 'created') {
            return ['start'];
        }
        return [];
    }
}