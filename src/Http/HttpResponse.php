<?php

namespace MadeiraMadeiraBr\HttpClient\Http;

use MadeiraMadeiraBr\HttpClient\BodyHandlers\IBodyHandler;

class HttpResponse implements IHttpResponse
{
    /**
     * @var string|null
     */
    private $method;

    /**
     * @var string|null
     */
    private $url;

    /**
     * @var int|null
     */
    private $status;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var array
     */
    private $options;

    /**
     * @var string|null
     */
    private $body;

    /**
     * @var HttpResponseTime
     */
    private $time;

    /**
     * @var IBodyHandler
     */
    private $bodyHandler;

    /**
     * @var int|null
     */
    private $errorCode;

    public function __construct(
        ?string $method = null,
        ?string $url = null,
        ?int $status = null,
        ?array $headers = null,
        ?array $options = null,
        ?string $body = null,
        ?HttpResponseTime $time = null)
    {
        $this->method = $method;
        $this->url = $url;
        $this->status = $status;
        $this->headers = $headers ?? [];
        $this->options = $options ?? [];
        $this->body = $body;
        $this->time = $time ?? new HttpResponseTime(0,0,0,0,0);
    }

    /**
     * @return string
     */
    public function getMethod(): ?string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @return array
     */
    public function getDecodedBody(): ?array
    {
        return $this->bodyHandler->decode($this->getBody());
    }

    /**
     * @param IBodyHandler $bodyHandler
     * @return IHttpResponse
     */
    public function setBodyHandler(IBodyHandler $bodyHandler): IHttpResponse
    {
        $this->bodyHandler = $bodyHandler;
        return $this;
    }

    /**
     * @return HttpResponseTime
     */
    public function getTime(): HttpResponseTime
    {
        return $this->time;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->getStatus(),
            'headers' => $this->getHeaders(),
            'time' => $this->getTime()->toArray(),
            'body' => $this->getBody(),
            'errorCode' => $this->getErrorCode()
        ];
    }

    /**
     * @return int|null
     */
    public function getErrorCode(): ?int
    {
        return $this->errorCode;
    }

    /**
     * @param int|null $errorCode
     * @return HttpResponse
     */
    public function setErrorCode(?int $errorCode): HttpResponse
    {
        $this->errorCode = $errorCode;
        return $this;
    }
}