<?php

namespace App\Services\Exchanges;

/**
 * Interface CoinListingInterface.
 */
interface CoinListingInterface
{
    public function getCoinList(): ?array;
}
