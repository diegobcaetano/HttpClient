<?php

namespace MadeiraMadeiraBr\HttpClient\Http;

interface ITransaction
{
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_PUT = 'PUT';
    const HTTP_METHOD_DELETE = 'DELETE';

    public function run(): ITransaction;
    public function getResponse(): IHttpResponse;
}