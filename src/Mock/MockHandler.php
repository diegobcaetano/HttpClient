<?php

namespace MadeiraMadeiraBr\HttpClient\Mock;

class MockHandler
{
    /**
     * @var MockHandler|null
     */
    private static $instance = null;

    /**
     * @var Mock[]
     */
    private static $mocks = [];

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if(self::$instance) return self::$instance;
        return new self;
    }

    public static function add(Mock $mock)
    {
        $hash = md5(strtolower($mock->getMethod()) . strtolower($mock->getUrl()));
        self::$mocks[$hash] = $mock;
    }

    public static function find(string $method, string $url): ?Mock
    {
        $url = rtrim($url,"/");
        $hash = md5(strtolower($method) . strtolower($url));
        return self::$mocks[$hash] ?? null;
    }
}