<?php


namespace App\Services\Exchanges;

/**
 * Interface CoinListingInterface
 * @package App\Services\Exchanges
 */
interface CoinListingInterface
{
    public function getCoinList(): ?array;
}
