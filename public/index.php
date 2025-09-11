<?php
/*
 * Copyright (c) 2025.
 */

use App\Controllers\ContainerController;
use App\Controllers\DashboardController;
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

// Dispatch
$request = Request::createFromGlobals();
$response = new Response();
$router->dispatch($request, $response);
