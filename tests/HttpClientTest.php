<?php

namespace MadeiraMadeiraBr\HttpClient\Tests;

use MadeiraMadeiraBr\HttpClient\Http\HttpClient;
use PHPUnit\Framework\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class HttpClientTest extends TestCase
{
    public function testRequestGet()
    {
        $response = (new HttpClient())->get('https://jsonplaceholder.typicode.com/posts');
        $this->assertIsArray($response);
        $this->assertEquals(100, count($response));
    }

    public function testRequestPost()
    {
        $response = (new HttpClient())->post('https://jsonplaceholder.typicode.com/posts', [
            'title' => 'foo',
            'body' => 'bar',
            'userId' => 1
        ]);
        $this->assertIsArray($response);
        $this->assertArrayHasKey('id', $response);
        $this->assertEquals($response['id'], 101);
    }

    public function testRequestPut()
    {
        $response = (new HttpClient())->put('https://jsonplaceholder.typicode.com/posts/1', [
            'id' => 1,
            'title' => 'foo',
            'body' => 'bar',
            'userId' => 1,
            'puttest' => [
                'status' => 1,
                'message' => 'OK'
            ]
        ]);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('puttest', $response);
        $this->assertArrayHasKey('status', $response['puttest']);
        $this->assertEquals($response['puttest']['status'], 1);
    }

    public function testRequestDelete()
    {
        $httpClient = new HttpClient();
        $httpClient->delete('https://jsonplaceholder.typicode.com/posts/1');
        $response = $httpClient->getLastResponse();
        $this->assertNotEmpty($response);
        $this->assertEquals(200, $response->getStatus());
    }
}