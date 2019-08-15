<?php

namespace MadeiraMadeiraBr\HttpClient\BodyHandlers;

interface IBodyHandler
{
    public function encode(array $body): ?string;
    public function decode(string $body): ?array;
}