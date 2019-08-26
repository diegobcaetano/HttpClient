<?php

namespace MadeiraMadeiraBr\HttpClient\Http;

use MadeiraMadeiraBr\HttpClient\Curl\CurlExtractor;

class Transaction implements ITransaction
{
    /**
     * @var IHttpRequest
     */
    private $request;

    /**
     * @var IHttpResponse
     */
    private $response;

    public function __construct(IHttpRequest $request)
    {
        $this->request = $request;
    }

    public function run(): ITransaction
    {
        $ch = $this->prepare();
        $return = curl_exec($ch);
        $this->response = (new CurlExtractor($ch, $return, $this->request))->getResponse();
        curl_close($ch);
        return $this;
    }

    private function prepare()
    {
        $method = ucfirst(strtolower($this->request->getMethod()));
        $curlBuilder = "MadeiraMadeiraBr\\HttpClient\\Curl\\{$method}CurlBuilder";
        if(!class_exists($curlBuilder)) {
            throw new \Exception('Method not allowed');
        }

        return (new $curlBuilder($this->request))->prepare();
    }

    public function getResponse(): IHttpResponse
    {
        return $this->response;
    }

    public function getRequest(): IHttpRequest
    {
        return $this->request;
    }

    public function toArray(): array
    {
        return [
            'request' => $this->getRequest()->toArray(),
            'response' => $this->getRequest()->toArray()
        ];
    }
}