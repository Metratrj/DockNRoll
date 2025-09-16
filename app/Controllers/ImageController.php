<?php

/*
 * Copyright (c) 2025.
 */

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Http\View;
use App\Services\ImageService;

class ImageController
{
    private ImageService $service;

    public function __construct(ImageService $service = new ImageService())
    {
        $this->service = $service;
    }

    public function index(Request $request, Response $response, View $view): string
    {
        return $view->render("images/list");
    }

    public function show(Request $request, Response $response, View $view, string $id): string
    {
        $image = $this->service->imageInspect($id);
        if ($image === null) {
            error_log("ImageInspect returned null for ID: " . $id);
            $response->setStatus(404);
            return "Image not found";
        }
        return $view->render("images/show", ["image" => $image]);
    }

    /**
     * Handles the raw data API request for images.
     */
    public function search(Request $request, Response $response): void
    {
        $result = $this->findImages($request);

        $response->setHeader("Content-Type", "application/json");
        $response->sendHeaders();
        $response->setBody(json_encode($result));
        $response->send();
        exit();
    }

    /**
     * Handles the request for rendering HTML table rows for images.
     */
    public function rows(Request $request, Response $response): void
    {
        $result = $this->findImages($request);

        $result["html"] = $this->renderImageRows($result["data"]);
        unset($result["data"]);

        $response->setHeader("Content-Type", "application/json");
        $response->sendHeaders();
        $response->setBody(json_encode($result));
        $response->send();
        exit();
    }

    /**
     * Centralized method to fetch and filter images based on request parameters.
     */
    private function findImages(Request $request): array
    {
        $params = $request->get;
        $page = (int) ($params["page"] ?? 1);
        $perPage = (int) ($params["per_page"] ?? 10);
        $sort = $params["sort"] ?? "";
        $order = $params["order"] ?? "desc";
        $search = trim($params["search"] ?? "");

        return $this->service->getImages($page, $perPage, $sort, $order, $search);
    }

    /**
     * Renders multiple table rows for a list of images.
     *
     * @param \OpenAPI\Client\Model\ImageSummary[] $images
     * @return string
     */
    private function renderImageRows(array $images): string
    {
        $html = "";
        foreach ($images as $image) {
            ob_start();
            include __DIR__ . "/../Views/images/_row.php";
            $html .= ob_get_clean();
        }
        return $html;
    }
}
