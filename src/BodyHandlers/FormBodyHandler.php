<?php

namespace MadeiraMadeiraBr\HttpClient\BodyHandlers;

class FormBodyHandler implements IBodyHandler
{
    public function encode(array $body): string
    {
        return http_build_query($body);
    }

    public function decode(string $body): array
    {
        parse_str($body, $output);
        return $output;
    }
}