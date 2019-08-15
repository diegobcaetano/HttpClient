<?php

namespace MadeiraMadeiraBr\HttpClient\Http;

use MadeiraMadeiraBr\HttpClient\BodyHandlers\IBodyHandler;

class HttpRequest implements IHttpRequest
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $body;

    /**
     * @var IBodyHandler
     */
    private $bodyHandler;

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return HttpRequest
     */
    public function setMethod(string $method): HttpRequest
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return HttpRequest
     */
    public function setUrl(string $url): HttpRequest
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return HttpRequest
     */
    public function setHeaders(array $headers): HttpRequest
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return HttpRequest
     */
    public function setOptions(array $options): HttpRequest
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return array
     */
    public function getBody(): array
    {
        return $this->body;
    }

    public function getEncodedBody(): string
    {
        return $this->bodyHandler->encode($this->getBody());
    }

    /**
     * @param array $body
     * @return HttpRequest
     */
    public function setBody(array $body): HttpRequest
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param IBodyHandler $bodyHandler
     * @return HttpRequest
     */
    public function setBodyHandler(IBodyHandler $bodyHandler): HttpRequest
    {
        $this->bodyHandler = $bodyHandler;
        return $this;
    }
}