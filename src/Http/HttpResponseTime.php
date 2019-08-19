<?php

namespace MadeiraMadeiraBr\HttpClient\Http;

use MadeiraMadeiraBr\Event\EventObserverFactory;
use MadeiraMadeiraBr\HttpClient\EnvConfigInterface;
use MadeiraMadeiraBr\HttpClient\Printable;

class HttpResponseTime implements Printable
{
    /**
     * @var float
     */
    private $total;

    /**
     * @var float
     */
    private $nameLookup;

    /**
     * @var float
     */
    private $connection;

    /**
     * @var float
     */
    private $handshake;

    /**
     * @var float
     */
    private $firstByte;

    public function __construct(
        ?float $total,
        ?float $nameLookup,
        ?float $connection,
        ?float $handshake,
        ?float $firstByte)
    {
        $this->total = $total;
        $this->nameLookup = $nameLookup;
        $this->connection = $connection;
        $this->firstByte = $firstByte;
        $this->handshake = $handshake;
    }

    /**
     * @return float
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @return float
     */
    public function getNameLookup(): ?float
    {
        return $this->nameLookup;
    }

    /**
     * @return float
     */
    public function getConnection(): ?float
    {
        return $this->connection;
    }

    /**
     * @return float
     */
    public function getHandshake(): ?float
    {
        return $this->handshake;
    }

    /**
     * @return float
     */
    public function getFirstByte(): ?float
    {
        return $this->firstByte;
    }

    public function checkSlowRequest(?float $slowRequestTime = null, ?array $eventInformation = [])
    {
        $slowRequestTime = $slowRequestTime ?? getenv(EnvConfigInterface::SLOW_REQUEST_ALERT);
        if(!$slowRequestTime) return;

        if($this->getTotal() >= $slowRequestTime) {
            EventObserverFactory::getInstance()
                ->dispatchEvent(EnvConfigInterface::SLOW_REQUEST_ALERT, $eventInformation);
        }
    }

    public function toArray(): array
    {
        return [
            'total' => $this->getTotal(),
            'nameLookup' => $this->getNameLookup(),
            'connection' => $this->getConnection(),
            'handshake' => $this->getHandshake(),
            'firstByte' => $this->getFirstByte()
        ];
    }
}