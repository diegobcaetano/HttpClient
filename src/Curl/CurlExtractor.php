<?php

namespace MadeiraMadeiraBr\HttpClient\Curl;

use MadeiraMadeiraBr\HttpClient\Http\HttpResponse;
use MadeiraMadeiraBr\HttpClient\Http\HttpResponseTime;
use MadeiraMadeiraBr\HttpClient\Http\IHttpRequest;
use MadeiraMadeiraBr\HttpClient\Http\IHttpResponse;

class CurlExtractor
{
    private $curlHandle;
    private $response;
    private $request;

    public function __construct($curlHandle, $response, IHttpRequest $request)
    {
        $this->curlHandle = $curlHandle;
        $this->response = $response;
        $this->request = $request;
    }

    public function getResponse(): IHttpResponse
    {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();
        $status = $this->extractStatus();
        $headers = $this->extractHeaders();
        $body = $this->extractBody();

        $totalTime = $this->extractTotalTime();
        $nameLookup = $this->extractNameLookupTime();
        $connection = $this->extractConnectionTime();
        $handshake = $this->extractHandshakeTime();
        $firstByteTime = $this->extractFirstByteTime();

        $time = new HttpResponseTime($totalTime, $nameLookup, $connection, $handshake, $firstByteTime);
        $response = (new HttpResponse($method, $url, $status, $headers, $this->request->getOptions(), $body, $time));

        $errorCode = $this->extractCurlErrorNumber();

        if($errorCode) {
            $response->setErrorCode($errorCode);
        }

        return $response;
    }

    private function extractCurlErrorNumber(): ?int
    {
        return curl_errno($this->curlHandle);
    }

    private function extractStatus(): ?int
    {
        return intval(curl_getinfo($this->curlHandle, CURLINFO_HTTP_CODE));
    }

    private function extractTotalTime(): ?float
    {
        return curl_getinfo($this->curlHandle, CURLINFO_TOTAL_TIME);
    }

    private function extractNameLookupTime(): ?float
    {
        return curl_getinfo($this->curlHandle, CURLINFO_NAMELOOKUP_TIME);
    }

    private function extractConnectionTime(): ?float
    {
        return curl_getinfo($this->curlHandle, CURLINFO_CONNECT_TIME);
    }

    private function extractHandshakeTime(): ?float
    {
        return curl_getinfo($this->curlHandle, CURLINFO_APPCONNECT_TIME);
    }

    private function extractFirstByteTime(): ?float
    {
        return curl_getinfo($this->curlHandle, CURLINFO_STARTTRANSFER_TIME);
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

    private function extractHeaderSize(): ?int
    {
        return curl_getinfo($this->curlHandle, CURLINFO_HEADER_SIZE);
    }

    private function extractBody(): ?string
    {
        return substr($this->response, $this->extractHeaderSize());
    }
}