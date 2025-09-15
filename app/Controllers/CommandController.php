<?php

namespace App\Controllers;

use App\Http\Response;
use App\Services\ContainerService;
use Predis\Client as RedisClient;

class CommandController
{
    private RedisClient $redis;
    private ContainerService $containerService;

    public function __construct()
    {
        $this->redis = new RedisClient([
            'scheme' => 'tcp',
            'host'   => 'localhost',
            'port'   => 6379,
        ]);
        $this->containerService = new ContainerService();
    }

    public function execute(): void
    {
        $requestBody = json_decode(file_get_contents('php://input'), true);
        $command = $requestBody['command'] ?? null;
        $targetName = $requestBody['target'] ?? null;

        if (!$command || !$targetName) {
            (new Response())->setStatus(400)->json(['error' => 'Command and target are required.']);
            return;
        }

        try {
            $targetId = $this->findContainerIdByName($targetName);

            if (!$targetId) {
                (new Response())->setStatus(404)->json(['error' => "Container '{$targetName}' not found."]);
                return;
            }

            switch ($command) {
                case 'start':
                    $this->containerService->containerStart($targetId);
                    $message = "Container {$targetName} started successfully.";
                    break;
                case 'stop':
                    $this->containerService->containerStop($targetId);
                    $message = "Container {$targetName} stopped successfully.";
                    break;
                default:
                    (new Response())->setStatus(400)->json(['error' => "Unknown command '{$command}'."]);
                    return;
            }

            (new Response())->json(['status' => 'success', 'message' => $message]);

        } catch (\Exception $e) {
            (new Response())->setStatus(500)->json([
                'error' => 'An error occurred while executing the command.',
                'message' => $e->getMessage()
            ]);
        }
    }

    private function findContainerIdByName(string $name): ?string
    {
        // Sanitize name similar to how it's indexed
        $sanitizedName = strtolower(ltrim($name, '/'));

        // We search for the full, sanitized name as a token
        $indexKey = "index:term:" . $sanitizedName;
        $itemKeys = $this->redis->smembers($indexKey);

        $foundContainerId = null;
        $count = 0;

        foreach ($itemKeys as $itemKey) {
            if (str_starts_with($itemKey, 'container:')) {
                // Additionally, check if the name is an exact match
                $redisName = ltrim($this->redis->hget($itemKey, 'name'), '/');
                if ($redisName === $sanitizedName) {
                    $foundContainerId = str_replace('container:', '', $itemKey);
                    $count++;
                }
            }
        }

        // Only return an ID if we found exactly one match
        if ($count === 1) {
            return $foundContainerId;
        }

        return null;
    }

    public function getCommands(): void
    {
        $commands = [
            ['name' => 'start', 'description' => 'Start a container.'],
            ['name' => 'stop', 'description' => 'Stop a container.'],
        ];
        (new Response())->json($commands);
    }
}
