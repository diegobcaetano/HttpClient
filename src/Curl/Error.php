<?php

namespace MadeiraMadeiraBr\HttpClient\Curl;

use MadeiraMadeiraBr\HttpClient\IError;

class Error implements IError
{
    /**
     * @return int
     */
    private $code;

    /**
     * @return string
     */
    private $description;

    /**
     * Error constructor.
     * @param $code
     * @param $description
     */
    public function __construct(int $code, string $description)
    {
        $this->code = $code;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'description' => $this->getDescription()
        ];
    }
}