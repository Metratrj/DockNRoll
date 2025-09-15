<?php

/*
 * Copyright (c) 2025.
 */

namespace App\Controllers;

use App\Http\View;
use App\Services\SystemService;

class DashboardController
{
    private SystemService $service;
    public function __construct(SystemService $service = new SystemService())
    {
        $this->service = $service;
    }

    public function index(): void
    {
        $sysinfo = $this->service->systemInfo();
        $sysdatainfo = $this->service->systemDataUsage();

        $stats = [
          "containers_running" => $sysinfo->getContainersRunning(),
          "images" => $sysinfo->getImages(),
          "volumes" => count($sysdatainfo->getVolumes()),
          "audit_logs" => 42,
        ];

        View::render("dashboard/index", [
          "title" => "Dashboard",
          "stats" => $stats,
        ]);
    }
}
