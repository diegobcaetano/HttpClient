<?php

namespace MadeiraMadeiraBr\HttpClient\Http;

use MadeiraMadeiraBr\HttpClient\BodyHandlers\IBodyHandler;
use MadeiraMadeiraBr\HttpClient\Printable;

interface IHttpResponse extends Printable
{
    /**
     * @return string
     */
    public function getMethod(): ?string;

    /**
     * @return string
     */
    public function getUrl(): ?string;

    /**
     * @return int
     */
    public function getStatus(): ?int;

    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @return array
     */
    public function getOptions(): array;

    /**
     * @return string
     */
    public function getBody(): ?string;

    /**
     * @return HttpResponseTime
     */
    public function getTime(): HttpResponseTime;

    /**
     * @return array
     */
    public function getDecodedBody(): ?array;

    /**
     * @return int|null
     */
    public function getErrorCode(): ?int;

    /**
     * @param IBodyHandler $bodyHandler
     * @return IHttpResponse
     */
    public function setBodyHandler(IBodyHandler $bodyHandler): IHttpResponse;
}