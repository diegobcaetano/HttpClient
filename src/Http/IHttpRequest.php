<?php

namespace MadeiraMadeiraBr\HttpClient\Http;

interface IHttpRequest
{
    public function getUrl(): string;
    public function getHeaders(): array;
    public function getMethod(): string;
    public function getOptions(): array;
    public function getBody(): array;
    public function getEncodedBody(): string;
}