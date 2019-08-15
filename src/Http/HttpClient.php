<?php

namespace MadeiraMadeiraBr\HttpClient\Http;

use MadeiraMadeiraBr\HttpClient\BodyHandlers\IBodyHandler;
use MadeiraMadeiraBr\HttpClient\BodyHandlers\JsonBodyHandler;

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
        $this->lastTransaction = (new Transaction(
            $this->buildRequest(
                ITransaction::HTTP_METHOD_GET,
                $this->getUrl($url, $options),
                null,
                $headers,
                $options)));

        $body = $this->lastTransaction->run()
            ->getResponse()
            ->setBodyHandler($this->responseBodyHandler)
            ->getDecodedBody();
        return $body;
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
        $this->lastTransaction = (new Transaction(
            $this->buildRequest(
                ITransaction::HTTP_METHOD_POST,
                $this->getUrl($url, $options),
                $body,
                $headers,
                $options)));

        $body = $this->lastTransaction->run()
            ->getResponse()
            ->setBodyHandler($this->responseBodyHandler)
            ->getDecodedBody();
        return $body;
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
        $this->lastTransaction = (new Transaction(
            $this->buildRequest(
                ITransaction::HTTP_METHOD_PUT,
                $this->getUrl($url, $options),
                $body,
                $headers,
                $options)));

        $body = $this->lastTransaction->run()
            ->getResponse()
            ->setBodyHandler($this->responseBodyHandler)
            ->getDecodedBody();
        return $body;
    }

    /**
     * @param string $url
     * @param array|null $headers
     * @param array|null $options
     * @return array|null
     */
    public function delete(string $url, ?array $headers = null, ?array $options = null): ?array
    {
        $this->lastTransaction = (new Transaction(
            $this->buildRequest(
                ITransaction::HTTP_METHOD_DELETE,
                $this->getUrl($url, $options),
                null,
                $headers,
                $options)));

        $body = $this->lastTransaction->run()
            ->getResponse()
            ->setBodyHandler($this->responseBodyHandler)
            ->getDecodedBody();
        return $body;
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
            ->setBodyHandler($this->requestBodyHandler);
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
       return $this->baseUrl . $url;
    }
}