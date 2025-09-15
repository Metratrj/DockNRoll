<?php

/*
 * Copyright (c) 2025.
 */

namespace App\Services;

use GuzzleHttp\Client;
use OpenAPI\Client\Api\ImageApi;
use OpenAPI\Client\ApiException;
use OpenAPI\Client\Configuration;
use OpenAPI\Client\Model\ErrorResponse;
use OpenAPI\Client\Model\ImageSummary;

class ImageService
{
    private ImageApi $service;

    public function __construct(string $host = "http://localhost:2375")
    {
        $this->guzzle = new Client([
          "base_uri" => $host,
          "timeout" => 0,
        ]);
        $config = Configuration::getDefaultConfiguration()->setHost($host);
        $this->service = new ImageApi($this->guzzle, $config);
    }

    /**
     * @param bool $all
     * @return ErrorResponse|ImageSummary[]
     */
    public function imageList(bool $all = true): \OpenAPI\Client\Model\ErrorResponse|array
    {
        $result = [];
        try {
            $result = $this->service->imageList($all);
        } catch (ApiException $e) {
            echo "Exception when calling ImageApi->imageList: ", $e->getMessage(), PHP_EOL;
        }
        return $result;
    }

    public function getImages(int $page, int $per_page, string $sort, string $order, string $search): array
    {
        $all = $this->imageList();

        // filter
        if ($search != "") {
            $search_lower = strtolower($search);
            $all = array_filter(
                $all,
                fn ($img) => str_contains(strtolower(implode(" ", $img->getRepoTags() ?? [])), $search_lower),
            );
        }

        // sort
        usort($all, function ($a, $b) use ($sort, $order) {
            $valueA = $a[$sort] ?? "";
            $valueB = $b[$sort] ?? "";
            return $order == "asc" ? $valueA <=> $valueB : $valueB <=> $valueA;
        });

        // pagination
        $total = count($all);
        $offset = (int) (($page - 1) * $per_page);
        $paged = array_slice($all, $offset, $per_page);

        return [
          "data" => $paged,
          "total" => $total,
          "page" => $page,
          "per_page" => $per_page,
        ];
    }
}
