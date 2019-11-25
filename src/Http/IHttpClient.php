<?php

namespace MadeiraMadeiraBr\HttpClient\Http;

interface IHttpClient
{
    /**
     * @param string $serviceName
     * @return IHttpClient
     */
    public function setServiceName(?string $serviceName): IHttpClient;

    /**
     * @return string
     */
    public function getServiceName(): ?string;

    /**
     * @param array $headers
     * @return IHttpClient
     */
    public function setHeaders(array $headers): IHttpClient;

    /**
     * @param array $header
     * @return $this
     */
    public function pushHeader(array $header): IHttpClient;

    /**
     * @param array $options
     * @return IHttpClient
     */
    public function setOptions(array $options): IHttpClient;

    /**
     * @param array $option
     * @return IHttpClient
     */
    public function pushOption(array $option): IHttpClient;

    /**
     * @param string $url
     * @param array|null $headers
     * @param array|null $options
     * @return array|null
     */
    public function get(string $url, ?array $headers = null, ?array $options = null): ?array;

    /**
     * @param string $url
     * @param array $body
     * @param array|null $headers
     * @param array|null $options
     * @return array|null
     */
    public function post(string $url, array $body, ?array $headers = null, ?array $options = null): ?array;

    /**
     * @param string $url
     * @param array $body
     * @param array|null $headers
     * @param array|null $options
     * @return array|null
     */
    public function put(string $url, array $body, ?array $headers = null, ?array $options = null): ?array;

    /**
     * @param string $url
     * @param array|null $headers
     * @param array|null $options
     * @return array|null
     */
    public function delete(string $url, ?array $headers = null, ?array $options = null): ?array;

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
        ?array $options = null): IHttpResponse;

    /**
     * @return ITransaction|null
     */
    public function getLastTransaction(): ?ITransaction;

    /**
     * @return IHttpResponse|null
     */
    public function getLastResponse(): ?IHttpResponse;

    /**
     * @return string
     */
    public function getBaseUrl(): string;

    /**
     * @param string $baseUrl
     * @return IHttpClient
     */
    public function setBaseUrl(string $baseUrl): IHttpClient;

    /**
     * @param string $url
     * @param array|null $options
     * @return string
     */
    public function getUrl(string $url, ?array $options): string;
}