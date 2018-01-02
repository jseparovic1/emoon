<?php

namespace App\Event;

use App\Entity\Coin;
use App\Entity\Exchange;
use Symfony\Component\EventDispatcher\Event;

class CoinAddedToExchangeEvent extends Event
{
    /**
     * @var Coin
     */
    private $coin;

    /**
     * @var Exchange
     */
    private $exchange;

    public function __construct(Coin $coin, Exchange $exchange)
    {
        $this->coin = $coin;
        $this->exchange = $exchange;
    }

    public function getCoin(): Coin
    {
        return $this->coin;
    }

    public function getExchange(): Exchange
    {
        return $this->exchange;
    }
}
