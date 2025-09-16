<?php

/*
 * Copyright (c) 2025.
 */

namespace App\Services;

use GuzzleHttp\Client;
use OpenAPI\Client\Api\ContainerApi;
use OpenAPI\Client\ApiException;
use OpenAPI\Client\Configuration;
use OpenAPI\Client\Model\ContainerInspectResponse;
use OpenAPI\Client\Model\ContainerStatsResponse;
use OpenAPI\Client\Model\ContainerSummary;
use Psr\Http\Message\StreamInterface;

class ContainerService
{
    private ContainerApi $service;
    private Client $guzzle;

    public function __construct(string $host = "http://localhost:2375")
    {
        $this->guzzle = new Client([
            "base_uri" => $host,
            "timeout" => 0,
        ]);
        $config = Configuration::getDefaultConfiguration()->setHost($host);
        $this->service = new ContainerApi($this->guzzle, $config);
    }

    /**
     * @return ContainerSummary[]
     */
    public function containerList(bool $all = true): array
    {
        $list = [];
        try {
            $list = $this->service->containerList($all);
        } catch (ApiException $e) {
            echo "Exception when calling ContainerApi->containerCreate: ", $e->getMessage(), PHP_EOL;
        }
        return $list;
    }

    public function containerStats(string $id, bool $stream = false): ContainerStatsResponse
    {
        $result = new ContainerStatsResponse();
        try {
            $result = $this->service->containerStats($id, $stream);
        } catch (ApiException $e) {
            echo "Exception when calling ContainerApi->containerStats: ", $e->getMessage(), PHP_EOL;
        }
        return $result;
    }

    public function containerStatsStream(string $id): ?StreamInterface
    {
        try {
            $request = $this->service->containerStatsRequest($id, true, false);
            $response = $this->guzzle->send($request, ["stream" => true]);
            return $response->getBody();
        } catch (ApiException $e) {
            echo "Exception when calling ContainerApi->containerStats: ", $e->getMessage(), PHP_EOL;
        }
        return null;
    }

    public function containerInspect(string $id): ContainerInspectResponse
    {
        $result = new ContainerInspectResponse();
        try {
            $result = $this->service->containerInspect($id, false);
        } catch (ApiException $e) {
            echo "Exception when calling ContainerApi->containerInspect: ", $e->getMessage(), PHP_EOL;
        }
        return $result;
    }

    public function containerStart(string $id): void
    {
        try {
            $this->service->containerStart($id);
        } catch (ApiException $e) {
            echo "Exception when calling ContainerApi->containerStart: ", $e->getMessage(), PHP_EOL;
        }
    }

    public function containerStop(string $id): void
    {
        try {
            $this->service->containerStop($id);
        } catch (ApiException $e) {
            echo "Exception when calling ContainerApi->containerStop: ", $e->getMessage(), PHP_EOL;
        }
    }
}
