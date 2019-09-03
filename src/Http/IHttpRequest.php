<?php

namespace MadeiraMadeiraBr\HttpClient\Http;

use MadeiraMadeiraBr\HttpClient\Printable;

interface IHttpRequest extends Printable
{
    public function getUrl(): string;
    public function getHeaders(): array;
    public function getMethod(): string;
    public function getOptions(): array;
    public function getBody(): array;
    public function getEncodedBody(): string;
}