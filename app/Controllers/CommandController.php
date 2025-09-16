<?php

/*
 * Copyright (c) 2025.
 */

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Services\ContainerService;
use Predis\Client as RedisClient;

class CommandController
{
    private RedisClient $redis;
    private ContainerService $containerService;
    private const INDEX_NAME = "docknroll_idx";

    public function __construct()
    {
        $this->redis = new RedisClient([
            "scheme" => "tcp",
            "host" => "localhost",
            "port" => 6379,
        ]);
        $this->containerService = new ContainerService();
    }

    public function execute(Request $request, Response $response): void
    {
        $command = $request->body["command"] ?? null;
        $targetName = $request->body["target"] ?? null;

        if (!$command || !$targetName) {
            $response->setStatus(400)->json(["error" => "Command and target are required."]);
            return;
        }

        try {
            $targetId = $this->findContainerIdByName($targetName);

            if (!$targetId) {
                $response->setStatus(404)->json(["error" => "Container '{$targetName}' not found."]);
                return;
            }

            switch ($command) {
                case "start":
                    $this->containerService->containerStart($targetId);
                    $message = "Container {$targetName} started successfully.";
                    break;
                case "stop":
                    $this->containerService->containerStop($targetId);
                    $message = "Container {$targetName} stopped successfully.";
                    break;
                default:
                    $response->setStatus(400)->json(["error" => "Unknown command '{$command}'."]);
                    return;
            }

            $response->json(["status" => "success", "message" => $message]);
        } catch (\Exception $e) {
            $response->setStatus(500)->json([
                "error" => "An error occurred while executing the command.",
                "message" => $e->getMessage(),
            ]);
        }
    }

    private function findContainerIdByName(string $name): ?string
    {
        $sanitizedName = strtolower(ltrim($name, "/"));

        // Prepare the name for RediSearch text query
        $queryName = preg_replace('/([\\\\\.<>{}[\]":;!@#$%^&*()\-+=~])/', '\\\\$1', $sanitizedName);

        $queryString = "(@nametag:{" . $queryName . "} @type:{container})";

        try {
            $rawResult = $this->redis->executeRaw(["FT.SEARCH", self::INDEX_NAME, $queryString, "RETURN", 1, '$']);

            $count = $rawResult[0];
            if ($count == 0) {
                return null;
            }

            $foundContainerId = null;
            $exactMatchCount = 0;

            for ($i = 1; $i < count($rawResult); $i += 2) {
                $docJson = $rawResult[$i + 1][1];
                $doc = json_decode($docJson, true);
                $fullObject = $doc["full_object"] ?? null;

                if ($fullObject && !empty($fullObject["Names"])) {
                    foreach ($fullObject["Names"] as $containerName) {
                        if (strtolower(ltrim($containerName, "/")) === $sanitizedName) {
                            $foundContainerId = $fullObject["Id"];
                            $exactMatchCount++;
                            break;
                        }
                    }
                }
            }

            if ($exactMatchCount === 1) {
                return $foundContainerId;
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }

    public function getCommands(Request $request, Response $response): void
    {
        $commands = [
            ["name" => "start", "description" => "Start a container."],
            ["name" => "stop", "description" => "Stop a container."],
        ];
        $response->json($commands);
    }
}
