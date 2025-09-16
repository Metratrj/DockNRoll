<?php

namespace Tests\Http;

use App\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testGetMethod()
    {
        $request = new Request(
            uri: '',
            method: 'POST',
            get: [],
            post: [],
            files: [],
            cookie: [],
            server: [],
            body: []
        );
        $this->assertEquals('POST', $request->getMethod());
    }

    public function testGetPath()
    {
        $request = new Request(
            uri: '/test/path?param=1',
            method: 'GET',
            get: [],
            post: [],
            files: [],
            cookie: [],
            server: [],
            body: []
        );
        $this->assertEquals('/test/path', $request->getPath());
    }

    public function testInputFromGet()
    {
        $request = new Request(
            uri: '',
            method: 'GET',
            get: ['name' => 'John'],
            post: [],
            files: [],
            cookie: [],
            server: [],
            body: []
        );
        $this->assertEquals('John', $request->input('name'));
    }

    public function testInputFromPost()
    {
        $request = new Request(
            uri: '',
            method: 'POST',
            get: [],
            post: ['name' => 'Jane'],
            files: [],
            cookie: [],
            server: [],
            body: []
        );
        $this->assertEquals('Jane', $request->input('name'));
    }

    public function testInputDefaultValue()
    {
        $request = new Request(
            uri: '',
            method: 'GET',
            get: [],
            post: [],
            files: [],
            cookie: [],
            server: [],
            body: []
        );
        $this->assertEquals('default', $request->input('nonexistent', 'default'));
    }
}
