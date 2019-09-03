<?php

namespace MadeiraMadeiraBr\HttpClient\Mock;

use MadeiraMadeiraBr\HttpClient\BodyHandlers\IBodyHandler;
use MadeiraMadeiraBr\HttpClient\BodyHandlers\JsonBodyHandler;
use MadeiraMadeiraBr\HttpClient\Http\HttpResponse;
use MadeiraMadeiraBr\HttpClient\Http\HttpResponseTime;

class Mock
{
    /**
     * @var int
     */
    private $status = 200;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $body;

    /**
     * @var IBodyHandler
     */
    private $bodyHandler;

    /**
     * Mock constructor.
     * @param string $method
     * @param string $url
     * @param string $body
     * @param int|null $status
     * @param IBodyHandler|null $bodyHandler
     */
    public function __construct(
        string $method,
        string $url,
        string $body,
        ?int $status = null,
        ?IBodyHandler $bodyHandler = null)
    {
        $url = rtrim($url,"/");
        $this->status = $status ?? $this->status;
        $this->url = $url;
        $this->method = $method;
        $this->body = $this->setBodyContent($body);
        $this->bodyHandler = $bodyHandler ?? new JsonBodyHandler();
    }

    public function get()
    {
        $response =  new HttpResponse(
            $this->method,
            $this->url,
            $this->status,
            [],
            [],
            $this->body,
            new HttpResponseTime(0, 0, 0, 0, 0));
        $response->setBodyHandler($this->bodyHandler);
        return $response;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    private function setBodyContent(string $body): string
    {
        return @file_exists( $body )
            ? file_get_contents( $body )
            : $body;
    }
}