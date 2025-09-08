<?php

namespace App\Services;

use GuzzleHttp\Client;
use OpenAPI\Client\Api\ContainerApi;
use OpenAPI\Client\ApiException;
use OpenAPI\Client\Configuration;
use OpenAPI\Client\Model\ContainerSummary;

class ContainerService
{
    private ContainerApi $service;

    public function __construct(string $host = 'http://localhost:2375') {
        $guzzle = new Client([
            'base_uri' => $host,
            'timeout' => 5,
        ]);
        $config = Configuration::getDefaultConfiguration()->setHost($host);
        $this->service = new ContainerApi($guzzle, $config);
    }

    /**
     * @return ContainerSummary[]
     */
    public function containerList(bool $all = true): array {
        $list = [];
        try {
            $list = $this->service->containerList($all);
        }
        catch (ApiException $e) {
            echo 'Exception when calling ContainerApi->containerCreate: ', $e->getMessage(), PHP_EOL;
        }
        return $list;
    }

    public function containerStart(string $id): void {
        try {
            $this->service->containerStart($id);
        }
        catch (ApiException $e) {
            echo 'Exception when calling ContainerApi->containerStart: ', $e->getMessage(), PHP_EOL;
        }
    }

    public function containerStop(string $id): void {
        try {
            $this->service->containerStop($id);
        }
        catch (ApiException $e) {
            echo 'Exception when calling ContainerApi->containerStart: ', $e->getMessage(), PHP_EOL;
        }
    }
}