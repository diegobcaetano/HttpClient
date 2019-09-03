<?php

namespace MadeiraMadeiraBr\HttpClient;

interface IError extends Printable
{
    public function getCode();

    public function getDescription();
}