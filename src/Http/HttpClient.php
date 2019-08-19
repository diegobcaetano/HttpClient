<?php

namespace MadeiraMadeiraBr\HttpClient\Http;

use MadeiraMadeiraBr\HttpClient\BodyHandlers\IBodyHandler;
use MadeiraMadeiraBr\HttpClient\BodyHandlers\JsonBodyHandler;
use MadeiraMadeiraBr\HttpClient\Mock\MockHandler;

class HttpClient
{
    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var array
     */
    private $headers = [
        'content-type' => 'application/json; charset=utf-8',
        'connection'  => 'keep-alive'
    ];

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var IBodyHandler
     */
    private $requestBodyHandler;

    /**
     * @var IBodyHandler
     */
    private $responseBodyHandler;

    /**
     * @var ITransaction
     */
    private $lastTransaction = null;

    /**
     * HttpClient constructor.
     * @param string|null $baseUrl
     * @param array|null $headers
     * @param array|null $options
     * @param IBodyHandler|null $requestBodyHandler
     * @param IBodyHandler|null $responseBodyHandler
     */
    public function __construct(
        ?string $baseUrl = null,
        ?array $headers = null,
        ?array $options = null,
        ?IBodyHandler $requestBodyHandler = null,
        ?IBodyHandler $responseBodyHandler = null)
    {
        $this->baseUrl = $baseUrl ?? '';
        $this->headers = $headers ?? $this->headers;
        $this->options = $options ?? $this->options;
        $this->requestBodyHandler = $requestBodyHandler ?? new JsonBodyHandler();
        $this->responseBodyHandler = $responseBodyHandler ?? new JsonBodyHandler();
    }

    /**
     * @param array $headers
     * @return HttpClient
     */
    public function setHeaders(array $headers): HttpClient
    {
        array_change_key_case($headers, CASE_LOWER);
        $this->headers = $headers;
        return $this;
    }

    /**
     * @param array $options
     * @return HttpClient
     */
    public function setOptions(array $options): HttpClient
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param string $url
     * @param array|null $headers
     * @param array|null $options
     * @return array|null
     */
    public function get(string $url, ?array $headers = null, ?array $options = null): ?array
    {
        return $this->request(ITransaction::HTTP_METHOD_GET, $url, null, $headers, $options)
            ->setBodyHandler($options['responseBodyHandler'] ?? $this->responseBodyHandler)
            ->getDecodedBody();
    }

    /**
     * @param string $url
     * @param array $body
     * @param array|null $headers
     * @param array|null $options
     * @return array|null
     */
    public function post(string $url, array $body, ?array $headers = null, ?array $options = null): ?array
    {
        return $this->request(ITransaction::HTTP_METHOD_POST, $url, $body, $headers, $options)
            ->setBodyHandler($options['responseBodyHandler'] ?? $this->responseBodyHandler)
            ->getDecodedBody();
    }

    /**
     * @param string $url
     * @param array $body
     * @param array|null $headers
     * @param array|null $options
     * @return array|null
     */
    public function put(string $url, array $body, ?array $headers = null, ?array $options = null): ?array
    {
        return $this->request(ITransaction::HTTP_METHOD_PUT, $url, $body, $headers, $options)
            ->setBodyHandler($options['responseBodyHandler'] ?? $this->responseBodyHandler)
            ->getDecodedBody();
    }

    /**
     * @param string $url
     * @param array|null $headers
     * @param array|null $options
     * @return array|null
     */
    public function delete(string $url, ?array $headers = null, ?array $options = null): ?array
    {
        return $this->request(ITransaction::HTTP_METHOD_DELETE, $url, null, $headers, $options)
            ->setBodyHandler($options['responseBodyHandler'] ?? $this->responseBodyHandler)
            ->getDecodedBody();
    }

    public function request(
        string $method,
        string $url,
        ?array $body = null,
        ?array $headers = null,
        ?array $options = null): IHttpResponse
    {
        $url = $this->getUrl($url, $options);
        $mock = MockHandler::find($method, $url);

        if($mock) {
            return $mock->get();
        }

        $this->lastTransaction = (new Transaction(
            $this->buildRequest(
                $method,
                $this->getUrl($url, $options),
                $body,
                $headers,
                $options)));

        return $this->lastTransaction->run()->getResponse();
    }

    /**
     * @return ITransaction|null
     */
    public function getLastTransaction(): ?ITransaction
    {
        return $this->lastTransaction;
    }

    /**
     * @return IHttpResponse|null
     */
    public function getLastResponse(): ?IHttpResponse
    {
        return $this->getLastTransaction() ? $this->lastTransaction->getResponse() : null;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array|null $body
     * @param array|null $headers
     * @param array|null $options
     * @return HttpRequest
     */
    private function buildRequest(
        string $method,
        string $url,
        ?array $body = null,
        ?array $headers = null,
        ?array $options = null)
    {
        return (new HttpRequest())
            ->setMethod($method)
            ->setUrl($url)
            ->setHeaders($headers ?? $this->headers)
            ->setOptions($options ?? $this->options)
            ->setBody($body ?? [])
            ->setBodyHandler($options['requestBodyHandler'] ??  $this->requestBodyHandler);
    }

    /**
     * @param string $url
     * @param array|null $options
     * @return string
     */
    private function getUrl(string $url, ?array $options)
    {
       if(isset($options['baseUrl'])) {
           return $options['baseUrl'] . $url;
       }
       return rtrim($this->baseUrl . $url,"/");
    }
}