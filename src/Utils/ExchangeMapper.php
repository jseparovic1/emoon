<?php

namespace App\Utils;

use App\Services\Exchanges\CoinBase;
use App\Services\Exchanges\CoinListingInterface;

class ExchangeMapper
{
    /** @var array */
    protected $exchanges;
    
    protected $exchangesNames = [
        CoinBase::class => 'coinbase'
    ];

    public function __construct(...$exchanges)
    {
        foreach ($exchanges as $exchange) {
            $this->exchanges[] = $exchange;
        }
    }

    public function getExchangeName(CoinListingInterface $exchange)
    {
        if (!array_key_exists(get_class($exchange), $this->exchangesNames)) {
            throw new \Exception("Exchange ". get_class($exchange). " not mapped");
        }

        return $this->exchangesNames[get_class($exchange)];
    }

    public function getExchanges()
    {
        return $this->exchanges;
    }
}