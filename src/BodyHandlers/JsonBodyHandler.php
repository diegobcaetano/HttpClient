<?php

namespace MadeiraMadeiraBr\HttpClient\BodyHandlers;

class JsonBodyHandler implements IBodyHandler
{
    public function encode(array $body): ?string
    {
       return json_encode($body);
    }

    public function decode(string $body): ?array
    {
        return json_decode($body, true);
    }
}