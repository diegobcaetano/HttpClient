<?php

namespace MadeiraMadeiraBr\HttpClient\Curl;

class DeleteCurlBuilder extends CurlBuilder
{
    public function prepare()
    {
        $ch = parent::prepare();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        return $ch;
    }
}