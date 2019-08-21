<?php

namespace MadeiraMadeiraBr\HttpClient\ResponseQualityAssurance;

use MadeiraMadeiraBr\Event\EventObserverFactory;
use MadeiraMadeiraBr\HttpClient\EnvConfigInterface;
use MadeiraMadeiraBr\HttpClient\Http\IHttpResponse;

class ResponseQualityAssurance
{
    /**
     * @return IHttpResponse
     */
    private $response;

    public function __construct(IHttpResponse $response)
    {
        $this->response = $response;
    }

    public function checkCompliance()
    {
        $this->successStatusCompliance();
    }

    private function successStatusCompliance(): void
    {
        if($this->response->getStatus() != 200) return;
        if(!strlen($this->response->getBody())) return;

        if(!$this->response->getDecodedBody()) {
            EventObserverFactory::getInstance()
                ->dispatchEvent(EnvConfigInterface::FALSE_POSITIVE_STATUS_ALERT, $this->response);
        }
    }
}