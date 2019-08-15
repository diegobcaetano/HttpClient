<?php

namespace MadeiraMadeiraBr\HttpClient\Http;

use MadeiraMadeiraBr\HttpClient\BodyHandlers\IBodyHandler;

interface IHttpResponse
{
    /**
     * @return int
     */
    public function getStatus(): ?int;

    /**
     * @return array
     */
    public function getHeaders(): ?array;

    /**
     * @return string
     */
    public function getBody(): ?string;

    /**
     * @return array
     */
    public function getDecodedBody(): ?array;

    /**
     * @param IBodyHandler $bodyHandler
     * @return IHttpResponse
     */
    public function setBodyHandler(IBodyHandler $bodyHandler): IHttpResponse;
}