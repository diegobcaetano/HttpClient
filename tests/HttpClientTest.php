<?php

namespace MadeiraMadeiraBr\HttpClient\Tests;

use MadeiraMadeiraBr\Event\EventObserverFactory;
use MadeiraMadeiraBr\HttpClient\BodyHandlers\JsonBodyHandler;
use MadeiraMadeiraBr\HttpClient\EnvConfigInterface;
use MadeiraMadeiraBr\HttpClient\Http\HttpClient;
use MadeiraMadeiraBr\HttpClient\Http\HttpRequest;
use MadeiraMadeiraBr\HttpClient\Http\HttpResponse;
use MadeiraMadeiraBr\HttpClient\Http\HttpResponseTime;
use MadeiraMadeiraBr\HttpClient\Http\IHttpResponse;
use MadeiraMadeiraBr\HttpClient\Http\ITransaction;
use MadeiraMadeiraBr\HttpClient\Http\Transaction;
use MadeiraMadeiraBr\HttpClient\Mock\Mock;
use MadeiraMadeiraBr\HttpClient\Mock\MockHandler;
use MadeiraMadeiraBr\HttpClient\ResponseQualityAssurance\ResponseQualityAssurance;
use MadeiraMadeiraBr\HttpClient\Tests\Stub\Observer;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

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

    public function testRequestMethod()
    {
        $httpClient = new HttpClient();
        $response = $httpClient->request('POST', 'https://jsonplaceholder.typicode.com/posts', [
            'title' => 'foo',
            'body' => 'bar',
            'userId' => 1
        ]);

        $this->assertInstanceOf(IHttpResponse::class, $response);
    }

    public function testSlowRequestEventObserver()
    {
        EventObserverFactory::getInstance()->addObserversToEvent('HTTP_CLIENT_SLOW_REQUEST_ALERT',
            [
                Observer::class
            ]);

        $httpClient = new HttpClient();
        $httpClient->get('https://jsonplaceholder.typicode.com/posts/1', null, ['slowRequestTime' => 0.01]);

        $this->assertInstanceOf(ITransaction::class, Observer::$eventResult);
    }

    public function testRequestMockWithFile()
    {
        MockHandler::add(new Mock(
            'GET',
            'https://jsonplaceholder.typicode.com/posts',
            __DIR__ .'/Stub/Response/sample.json'));
        $response = (new HttpClient())->get('https://jsonplaceholder.typicode.com/posts');

        $this->assertIsArray($response);
        $this->assertArrayHasKey('test', $response);
        $this->assertEquals('ok', $response['test']);
    }

    public function testRequestMockWithString()
    {
        MockHandler::add(new Mock(
            'GET',
            'https://jsonplaceholder.typicode.com/posts',
            '{"test":"ok"}'));
        $response = (new HttpClient())->get('https://jsonplaceholder.typicode.com/posts');

        $this->assertIsArray($response);
        $this->assertArrayHasKey('test', $response);
        $this->assertEquals('ok', $response['test']);
    }

    public function testResponseFalsePositiveCompliance()
    {
        EventObserverFactory::getInstance()->addObserversToEvent(
            'HTTP_CLIENT_FALSE_POSITIVE_STATUS_ALERT',
            [
                Observer::class
            ]);

        $response = new HttpResponse(
            'GET',
            'fake.com',
            200,
            [],
            [],
            'Content should be a valid JSON, but it is not',
            new HttpResponseTime(0,0,0,0,0));
        $response->setBodyHandler(new JsonBodyHandler());

        $transaction = new Transaction(new HttpRequest());
        $reflection = new ReflectionClass($transaction);
        $responseProperty = $reflection->getProperty('response');
        $responseProperty->setAccessible(true);
        $responseProperty->setValue($transaction, $response);
        $responseProperty->setAccessible(false);

        (new ResponseQualityAssurance($transaction))->checkCompliance();

        $this->assertInstanceOf(ITransaction::class, Observer::$eventResult);
    }

    public function testResponseStatusCompliance()
    {
        EventObserverFactory::getInstance()->addObserversToEvent(
            EnvConfigInterface::UNEXPECTED_RESPONSE_STATUS_ALERT,
            [
                Observer::class
            ]);

        $response = new HttpResponse(
            'POST',
            'fake.com',
            500,
            [],
            ['unexpectedStatus' => [400, 500, 502]],
            'blabla',
            new HttpResponseTime(0,0,0,0,0));
        $response->setBodyHandler(new JsonBodyHandler());

        $transaction = new Transaction(new HttpRequest());
        $reflection = new ReflectionClass($transaction);
        $responseProperty = $reflection->getProperty('response');
        $responseProperty->setAccessible(true);
        $responseProperty->setValue($transaction, $response);
        $responseProperty->setAccessible(false);

        (new ResponseQualityAssurance($transaction))->checkCompliance();

        $this->assertInstanceOf(ITransaction::class, Observer::$eventResult);
    }

    public function testCurlErrorCompliance()
    {
        EventObserverFactory::getInstance()->addObserversToEvent('HTTP_CLIENT_CURL_ERROR',
            [
                Observer::class
            ]);

        $httpClient = new HttpClient();
        $httpClient->get('https://jsonplaceholder.typicode.com/posts/1',
            null,
            ['curlSettings' => [CURLOPT_TIMEOUT_MS => 10]]);

        $this->assertInstanceOf(ITransaction::class, Observer::$eventResult);
    }
}