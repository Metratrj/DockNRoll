<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Container Liste</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body{
            background: #0d1117;
            color: #fff;

        }

        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.5em; border: 1px solid #ccc; }
        .status-running { color: green; font-weight: bold; }
        .status-exited { color: red; font-weight: bold; }
        button { margin: 0 0.2em; }
    </style>
</head>
<body>

<?php

use App\Controllers\ContainerController;
use App\Controllers\DashboardController;
use App\Http\Request;
use App\Http\Response;
use App\Http\Router;

require __DIR__ . '/../vendor/autoload.php';

$router = new Router();

// Dashboard
$router->get('/', [DashboardController::class, 'index']);

// Container
$router->get('/containers', [ContainerController::class, 'index']);
$router->get('/containers/{id}', [ContainerController::class, 'show']);
$router->post('/containers/{id}/start', [ContainerController::class, 'start']);
$router->post('/containers/{id}/stop', [ContainerController::class, 'stop']);

// Dispatch
$request = Request::createFromGlobals();
$response = new Response();
$router->dispatch($request, $response);

?>

</body>
</html>