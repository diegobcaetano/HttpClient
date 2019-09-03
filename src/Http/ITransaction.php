<?php

namespace MadeiraMadeiraBr\HttpClient\Http;

use MadeiraMadeiraBr\HttpClient\Printable;

interface ITransaction extends Printable
{
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_PUT = 'PUT';
    const HTTP_METHOD_DELETE = 'DELETE';

    public function run(): ITransaction;
    public function getResponse(): IHttpResponse;
    public function getRequest(): IHttpRequest;
}