<?php

namespace MadeiraMadeiraBr\HttpClient\Mock;

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
     * Mock constructor.
     * @param string $method
     * @param string $url
     * @param string $body
     * @param int|null $status
     */
    public function __construct(
        string $method,
        string $url,
        string $body,
        ?int $status = null)
    {
        $url = rtrim($url,"/");
        $this->status = $status ?? $this->status;
        $this->url = $url;
        $this->method = $method;
        $this->body = $this->setBodyContent($body);
    }

    public function get()
    {
        return new HttpResponse(
            $this->status,
            [],
            $this->body,
            new HttpResponseTime(0, 0, 0, 0, 0));
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

    private function setBodyContent(string $body)
    {
        return @file_exists( $body )
            ? file_get_contents( $body )
            : $body;
    }
}