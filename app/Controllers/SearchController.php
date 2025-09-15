<?php

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use Predis\Client as RedisClient;

class SearchController
{
    private RedisClient $redis;

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

            $itemKeys = [];

            if (empty($query)) {
                if ($typeFilter) {
                    // If query is empty but a type is specified, return all items of that type
                    $itemKeys = $this->redis->smembers('index:type:' . strtolower($typeFilter));
                } else {
                    (new Response())->json([]);
                    return;
                }
            } else {
                $tokens = preg_split('/[\s_.-:]+/', $query);
                $tokens = array_filter($tokens, fn($t) => !empty($t));

                if (empty($tokens)) {
                    (new Response())->json([]);
                    return;
                }

                $indexKeys = array_map(fn($t) => 'index:term:' . $t, $tokens);
                $itemKeys = $this->redis->sinter($indexKeys);
            }

            $results = [
                'Container' => [],
                'Image' => [],
            ];

            foreach ($itemKeys as $itemKey) {
                $data = $this->redis->hgetall($itemKey);
                if (str_starts_with($itemKey, 'container:')) {
                    if ($typeFilter && strtolower($typeFilter) !== 'container') continue;
                    $data['type'] = 'Container';
                    $data['actions'] = $this->getContainerActions($data['state'] ?? '');
                    $results['Container'][] = $data;
                } elseif (str_starts_with($itemKey, 'image:')) {
                    if ($typeFilter && strtolower($typeFilter) !== 'image') continue;
                    $data['type'] = 'Image';
                    $data['actions'] = []; // No actions for images yet
                    $results['Image'][] = $data;
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

    public function debugKey(Request $request, Response $response, string $key): void
    {
        try {
            $members = $this->redis->smembers($key);
            (new Response())->json($members);
        } catch (\Exception $e) {
            (new Response())->setStatus(500)->json([
                'error' => 'Failed to query Redis key.',
                'message' => $e->getMessage()
            ]);
        }
    }
}
