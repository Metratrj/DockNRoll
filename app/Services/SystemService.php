<?php

/*
 * Copyright (c) 2025.
 */

namespace App\Services;

use GuzzleHttp\Client;
use OpenAPI\Client\Api\SystemApi;
use OpenAPI\Client\ApiException;
use OpenAPI\Client\Configuration;
use OpenAPI\Client\Model\SystemDataUsageResponse;
use OpenAPI\Client\Model\SystemInfo;

class SystemService
{
    private SystemApi $service;

    public function __construct(string $host = "http://localhost:2375")
    {
        $guzzle = new Client([
            "base_uri" => $host,
            "timeout" => 5,
        ]);
        $config = Configuration::getDefaultConfiguration()->setHost($host);
        $this->service = new SystemApi($guzzle, $config);
    }

    public function systemInfo(): SystemInfo
    {
        $result = new SystemInfo();
        try {
            $result = $this->service->systemInfo();
        } catch (ApiException $e) {
            echo "Exception when calling SystemApi->systemInfo: ", $e->getMessage(), PHP_EOL;
        }
        return $result;
    }

    public function systemDataUsage(): SystemDataUsageResponse
    {
        $result = new SystemDataUsageResponse();
        try {
            $result = $this->service->systemDataUsage();
        } catch (ApiException $e) {
            echo "Exception when calling SystemApi->systemDataUsage: ", $e->getMessage(), PHP_EOL;
        }
        return $result;
    }
}
