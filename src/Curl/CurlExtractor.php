<?php

namespace MadeiraMadeiraBr\HttpClient\Curl;

use MadeiraMadeiraBr\HttpClient\Http\HttpResponse;
use MadeiraMadeiraBr\HttpClient\Http\IHttpResponse;

class CurlExtractor
{
    private $curlHandle;
    private $response;

    public function __construct($curlHandle, $response)
    {
        $this->curlHandle = $curlHandle;
        $this->response = $response;
    }

    public function getResponse(): IHttpResponse
    {
        $status = $this->extractStatus();
        $headers = $this->extractHeaders();
        $body = $this->extractBody();

        return (new HttpResponse($status, $headers, $body));
    }

    private function extractStatus(): int
    {
        return intval(curl_getinfo($this->curlHandle, CURLINFO_HTTP_CODE));
    }

    private function extractHeaders(): array
    {
        $stringHeader = substr($this->response, 0, strpos($this->response, "\r\n\r\n"));
        $headers = [];
        foreach (explode("\r\n", $stringHeader) as $i => $line) {
            if ($i === 0) {
                $headers['Http-Code'] = $line;
                continue;
            }

            list ($key, $value) = explode(': ', $line);
            $headers[$key] = $value;
        }
        return $headers;
    }

    private function extractHeaderSize(): int
    {
        return curl_getinfo($this->curlHandle, CURLINFO_HEADER_SIZE);
    }

    private function extractBody(): string
    {
        return substr($this->response, $this->extractHeaderSize());
    }
}