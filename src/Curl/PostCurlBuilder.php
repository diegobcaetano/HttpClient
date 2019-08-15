<?php

namespace MadeiraMadeiraBr\HttpClient\Curl;

class PostCurlBuilder extends CurlBuilder
{
    public function prepare()
    {
        $ch = parent::prepare();
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request->getEncodedBody());
        return $ch;
    }
}