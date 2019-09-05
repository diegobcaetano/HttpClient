<?php

namespace MadeiraMadeiraBr\HttpClient\Http;

class CacheProxyHttpClient implements IHttpClient
{
    /**
     * @var array
     */
    const SUCCESS_STATUS = [200, 201];

    /**
     * @var array
     */
    private $cache = [];

    /**
     * @var IHttpClient
     */
    private $httpClient;

    /**
     * CacheProxyHttpClient constructor.
     * @param IHttpClient $httpClient
     */
    public function __construct(IHttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param array $headers
     * @return IHttpClient
     */
    public function setHeaders(array $headers): IHttpClient
    {
        return $this->httpClient->setHeaders($headers);
    }

    /**
     * @param array $header
     * @return IHttpClient
     */
    public function pushHeader(array $header): IHttpClient
    {
        return $this->httpClient->pushHeader($header);
    }

    /**
     * @param array $options
     * @return IHttpClient
     */
    public function setOptions(array $options): IHttpClient
    {
        return $this->httpClient->setOptions($options);
    }

    /**
     * @param array $option
     * @return IHttpClient
     */
    public function pushOption(array $option): IHttpClient
    {
        return $this->httpClient->pushOption($option);
    }

    /**
     * @param string $url
     * @param array|null $headers
     * @param array|null $options
     * @return array|null
     */
    public function get(string $url, ?array $headers = null, ?array $options = null): ?array
    {
        return $this->request(ITransaction::HTTP_METHOD_GET, $url, null, $headers, $options)
            ->getDecodedBody();
    }

    /**
     * @param string $url
     * @param array $body
     * @param array|null $headers
     * @param array|null $options
     * @return array|null
     */
    public function post(string $url, array $body, ?array $headers = null, ?array $options = null): ?array
    {
        return $this->request(ITransaction::HTTP_METHOD_POST, $url, $body, $headers, $options)
            ->getDecodedBody();
    }

    /**
     * @param string $url
     * @param array $body
     * @param array|null $headers
     * @param array|null $options
     * @return array|null
     */
    public function put(string $url, array $body, ?array $headers = null, ?array $options = null): ?array
    {
        return $this->request(ITransaction::HTTP_METHOD_PUT, $url, $body, $headers, $options)
            ->getDecodedBody();
    }

    /**
     * @param string $url
     * @param array|null $headers
     * @param array|null $options
     * @return array|null
     */
    public function delete(string $url, ?array $headers = null, ?array $options = null): ?array
    {
        return $this->request(ITransaction::HTTP_METHOD_DELETE, $url, null, $headers, $options)
            ->getDecodedBody();
    }

    /**
     * @param string $method
     * @param string $url
     * @param array|null $body
     * @param array|null $headers
     * @param array|null $options
     * @return IHttpResponse
     */
    public function request(
        string $method,
        string $url,
        ?array $body = null,
        ?array $headers = null,
        ?array $options = null): IHttpResponse
    {
        $requestHash = $this->generateRequestHash($method, $this->getUrl($url, $options), $body);
        if(isset($this->cache[$requestHash])) {
            return $this->cache[$requestHash];
        }
        $response = $this->httpClient->request($method, $url, $body, $headers, $options);
        if(in_array($response->getStatus(), self::SUCCESS_STATUS)  ) {
            $this->cacheResponse($requestHash);
        }
        return $response;
    }

    /**
     * @return ITransaction|null
     */
    public function getLastTransaction(): ?ITransaction
    {
        return $this->httpClient->getLastTransaction();
    }

    /**
     * @return IHttpResponse|null
     */
    public function getLastResponse(): ?IHttpResponse
    {
        return $this->httpClient->getLastResponse();
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->httpClient->getBaseUrl();
    }

    /**
     * @param string $baseUrl
     * @return IHttpClient
     */
    public function setBaseUrl(string $baseUrl): IHttpClient
    {
        return $this->httpClient->setBaseUrl($baseUrl);
    }

    public function getUrl(string $url, ?array $options): string
    {
        return $this->httpClient->getUrl($url, $options);
    }

    private function cacheResponse(string $hash): bool
    {
        $this->cache[$hash] = $this->getLastTransaction()->getResponse();
        return true;
    }

    private function generateRequestHash(string $method, string $url, ?array $body = null): int
    {
        $body = json_encode($body) ?? '';
        return crc32($method . $url . $body);
    }
}