<?php

namespace App\Controllers;

use App\Services\SystemService;

class SystemController
{
    private SystemService $service;

    public function __construct(SystemService $service = new SystemService()) {
        $this->service = $service;
    }
}