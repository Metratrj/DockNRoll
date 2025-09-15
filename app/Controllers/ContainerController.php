<?php

/*
 * Copyright (c) 2025.
 */

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Http\View;
use App\Services\ContainerService;

class ContainerController
{
    private ContainerService $service;

    public function __construct(ContainerService $service = new ContainerService())
    {
        $this->service = $service;
    }

    public function index(): void
    {
        $containers = $this->service->containerList();
        View::render("containers/list", ["containers" => $containers]);
    }

    public function show(Request $request, Response $response, string $id): void
    {
        $stats = $this->service->containerStats($id);
        View::render("containers/show", ["stats" => $stats]);
    }

    public function statsStream(Request $request, Response $response, string $id): void
    {
        $stream = $this->service->containerStatsStream($id);

        $response
          ->setHeader("Content-Type", "text/event-stream")
          ->setHeader("Cache-Control", "no-cache")
          ->setHeader("Connection", "keep-alive")
          ->sendHeaders();

        while (!$stream->eof()) {
            $line = $stream->read(1024);
            if (empty($line)) {
                continue;
            }
            echo "data: " . $line . "\n\n";
            ob_flush();
            flush();
        }
    }

    public function start(Request $request, Response $response, string $id): void
    {
        $this->service->containerStart($id);
        $response->setStatus(302)->setHeader("Location", "/containers")->send();
    }

    public function stop(Request $request, Response $response, string $id): void
    {
        $this->service->containerStop($id);
        $response->setStatus(302)->setHeader("Location", "/containers")->send();
    }
}
