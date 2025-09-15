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

    public function index(): void
    {
        $images = $this->service->imageList();
        View::render("images/list", ["images" => $images]);
    }

    public function search(Request $request, Response $response): void
    {
        $response->setHeader("Content-Type", "application/json");
        $response->sendHeaders();
        $page = (int) ($_GET["page"] ?? 1);
        $perPage = (int) ($_GET["per_page"] ?? 10);
        $sort = $_GET["sort"] ?? "";
        $order = $_GET["order"] ?? "desc";
        $search = trim($_GET["search"] ?? "");

        $result = $this->service->getImages($page, $perPage, $sort, $order, $search);
        $response->setBody(json_encode($result));
        $response->send();
        exit();
    }
}
