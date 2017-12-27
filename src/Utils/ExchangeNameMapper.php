<?php

namespace App\Utils;


use App\Services\Exchanges\CoinBase;
use App\Services\Exchanges\CoinListingInterface;

class ExchangeNameMapper
{
    protected $exchanges = [
        CoinBase::class => 'coinbase'
    ];

    public function getExchangeName(CoinListingInterface $exchange)
    {
        return $this->exchanges[get_class($exchange)];
    }
}
