<?php

/*
 * Copyright (c) 2025.
 */

use App\Controllers\ContainerController;
use App\Controllers\DashboardController;
use App\Controllers\ImageController;
use App\Controllers\SearchController;
use App\Controllers\CommandController;
use App\Http\Request;
use App\Http\Response;
use App\Http\Router;

require __DIR__ . "/../vendor/autoload.php";

$router = new Router();

// Dashboard
$router->get("/", [DashboardController::class, "index"]);

// Container
$router->get("/containers", [ContainerController::class, "index"]);
$router->get("/containers/{id}", [ContainerController::class, "show"]);
$router->post("/containers/{id}/start", [ContainerController::class, "start"]);
$router->post("/containers/{id}/stop", [ContainerController::class, "stop"]);
$router->get("/containers/{id}/stats", [ContainerController::class, "statsStream"]);

$router->get("/images", [ImageController::class, "index"]);
$router->get("/images/search", [ImageController::class, "search"]);

// Search & Command API
$router->post("/api/search", [SearchController::class, "search"]);
$router->post("/api/command", [CommandController::class, "execute"]);
$router->get("/api/commands", [CommandController::class, "getCommands"]);

// Dispatch
$request = Request::createFromGlobals();
$response = new Response();
$router->dispatch($request, $response);
