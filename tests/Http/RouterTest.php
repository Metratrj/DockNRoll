<?php

namespace Tests\Http;

use App\Http\Request;
use App\Http\Response;
use App\Http\Router;
use App\Http\View;
use PHPUnit\Framework\TestCase;

// A stub controller for testing purposes
class TestController
{
    public function index(Request $req, Response $res, View $view): string
    {
        return 'Hello from Controller';
    }

    public function show(Request $req, Response $res, View $view, string $id): string
    {
        return "User ID: {$id}";
    }
}

class RouterTest extends TestCase
{
    private Router $router;
    private Response $response;

    protected function setUp(): void
    {
        $this->router = new Router();
        $this->response = new Response();
    }

    public function testDispatchesSimpleGetRouteWithClosure()
    {
        $this->router->get('/test', function (Request $req, Response $res, View $view) {
            return 'Hello from Closure';
        });

        $request = new Request('/test', 'GET', [], [], [], [], [], []);
        $this->router->dispatch($request, $this->response);

        $this->assertEquals('Hello from Closure', $this->response->getBody());
        $this->assertEquals(200, $this->response->getStatus());
    }

    public function testDispatchesRouteWithControllerAction()
    {
        $this->router->get('/controller', [TestController::class, 'index']);

        $request = new Request('/controller', 'GET', [], [], [], [], [], []);
        $this->router->dispatch($request, $this->response);

        $this->assertEquals('Hello from Controller', $this->response->getBody());
    }

    public function testDispatchesRouteWithParameters()
    {
        $this->router->get('/users/{id}', [TestController::class, 'show']);

        $request = new Request('/users/123', 'GET', [], [], [], [], [], []);
        $this->router->dispatch($request, $this->response);

        $this->assertEquals('User ID: 123', $this->response->getBody());
    }

    public function testReturns404ForNotFoundRoute()
    {
        $request = new Request('/non-existent', 'GET', [], [], [], [], [], []);
        $this->router->dispatch($request, $this->response);

        $this->assertEquals(404, $this->response->getStatus());
        $this->assertEquals('404 Not Found', $this->response->getBody());
    }

    public function testDispatchesPostRoute()
    {
        $this->router->post('/submit', function () {
            return 'Posted';
        });

        $request = new Request('/submit', 'POST', [], [], [], [], [], []);
        $this->router->dispatch($request, $this->response);

        $this->assertEquals('Posted', $this->response->getBody());
    }
}
