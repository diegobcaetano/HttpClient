<?php

namespace MadeiraMadeiraBr\HttpClient\Http;

use MadeiraMadeiraBr\HttpClient\BodyHandlers\IBodyHandler;

class HttpResponse implements IHttpResponse
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $body;

    /**
     * @var IBodyHandler
     */
    private $bodyHandler;

    public function __construct(int $status, array $headers, string $body)
    {
        $this->status = $status;
        $this->headers = $headers;
        $this->body = $body;
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
    public function getHeaders(): ?array
    {
        return $this->headers;
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
}