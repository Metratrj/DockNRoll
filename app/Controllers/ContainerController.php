<?php

namespace App\Controllers;

use App\Services\ContainerService;
use App\Utils\View;

class ContainerController
{
    private ContainerService $service;

    public function __construct(ContainerService $service = new ContainerService())
    {
        $this->service = $service;
    }

    public function index(): void {
        $containers = $this->service->containerList();
        View::render(__DIR__.'/../Views/containers/list.php', ['containers' => $containers]);
        // include __DIR__ . '/../Views/containers/list.php';
    }

    public function start(string $id): void {
        $this->service->containerStart($id);
        header('Location: /containers');
    }

    public function stop(string $id): void {
        $this->service->containerStop($id);
        header('Location: /containers');
    }
}