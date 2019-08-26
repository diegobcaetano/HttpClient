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
        $this->responseStatusCompliance();
        $this->slowRequestCompliance();
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

    private function slowRequestCompliance(): void
    {
        if(empty($this->response->getOptions()['slowRequestTime'])
            && !getenv(EnvConfigInterface::SLOW_REQUEST_ALERT) ) {
            return;
        }

        $slowRequestTime = isset($this->response->getOptions()['slowRequestTime'])
            ? floatval($this->response->getOptions()['slowRequestTime'])
            : floatval(getenv(EnvConfigInterface::SLOW_REQUEST_ALERT));

        if($this->response->getTime()->getTotal() >= $slowRequestTime) {
            EventObserverFactory::getInstance()
                ->dispatchEvent(EnvConfigInterface::SLOW_REQUEST_ALERT, $this->response);
        }
    }

    private function responseStatusCompliance(): void
    {
        $unexpectedStatus = isset($this->response->getOptions()['unexpectedStatus'])
            ? $this->response->getOptions()['unexpectedStatus']
            : array_filter(explode('|',
                getenv(EnvConfigInterface::UNEXPECTED_RESPONSE_STATUS_ALERT)));

        if(in_array($this->response->getStatus(), $unexpectedStatus)) {
            EventObserverFactory::getInstance()
                ->dispatchEvent(EnvConfigInterface::UNEXPECTED_RESPONSE_STATUS_ALERT, $this->response);
        }
    }
}