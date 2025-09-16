<?php

namespace Tests\Http;

use App\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testJson()
    {
        $response = new Response();
        $data = ['foo' => 'bar'];
        $response->json($data);

        $this->assertEquals(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), $response->getBody());
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals(['Content-Type' => 'application/json'], $response->getHeaders());
    }

    public function testConstructor()
    {
        $response = new Response();
        $this->assertEquals('', $response->getBody());
        $this->assertEquals(200, $response->getStatus());
    }

    public function testSetStatus()
    {
        $response = new Response();
        $response->setStatus(404);
        $this->assertEquals(404, $response->getStatus());
    }

    public function testSetBody()
    {
        $response = new Response();
        $response->setBody('test body');
        $this->assertEquals('test body', $response->getBody());
    }

    public function testSetHeader()
    {
        $response = new Response();
        $response->setHeader('X-Test', 'Test-Value');
        $this->assertEquals(['X-Test' => 'Test-Value'], $response->getHeaders());
    }

    public function testSetHeaders()
    {
        $response = new Response();
        $headers = ['X-Test' => 'Test-Value', 'X-Another' => 'Another-Value'];
        $response->setHeaders($headers);
        $this->assertEquals($headers, $response->getHeaders());
    }
}
