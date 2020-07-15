<?php

namespace MadeiraMadeiraBr\HttpClient\Curl;

use MadeiraMadeiraBr\HttpClient\Http\IHttpRequest;

abstract class CurlBuilder
{
    protected $request;
    protected $allowedCurlOptions = [
        CURLOPT_MAXREDIRS => true,
        CURLOPT_CONNECTTIMEOUT => true,
        CURLOPT_DNS_CACHE_TIMEOUT => true,
        CURLOPT_HTTP_VERSION => true,
        CURLOPT_MAXCONNECTS => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_POSTREDIR => true,
        CURLOPT_TIMEOUT => true,
        CURLOPT_TIMEOUT_MS => true,
        CURLOPT_COOKIE => true,
        CURLOPT_USERAGENT => true
    ];

    public function __construct(IHttpRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @return resource|false a cURL handle on success, false on errors.
     */
    public function prepare()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->request->getUrl());
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);

        $curlOptions = $this->request->getOptions()['curlSettings'] ?? [];
        foreach($curlOptions as $key => $option) {
            if(!isset($this->allowedCurlOptions[$key])) continue;
            curl_setopt($ch, $key, $option);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->prepareHeaders());

        return $ch;
    }

    protected function prepareHeaders()
    {
        $headers = [];
        foreach ($this->request->getHeaders() as $key => $header) {
            $headers[] = "$key: $header";
        }
        return $headers;
    }
}
