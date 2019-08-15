<?php

namespace MadeiraMadeiraBr\HttpClient\Curl;

class PutCurlBuilder extends CurlBuilder
{
    public function prepare()
    {
        $ch = parent::prepare();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request->getEncodedBody());
        return $ch;
    }
}