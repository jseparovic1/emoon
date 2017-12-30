<?php

namespace App\Services\Exchanges;

/**
 * Class Exchange
 * @package App\Services\Exchanges
 */
abstract class Exchange
{
    protected $mapper = [
        'XBT' => 'BTC',
        'BCC' => 'BCH',
        'DRK' => 'DASH'
    ];

    /**
     * Returns normalized coin symbol
     *
     * @param $symbol
     * @return mixed
     */
    public function getNormalizedCoinSymbol($symbol)
    {
        if (array_key_exists($symbol, $this->mapper)) {
            $symbol = $this->mapper[$symbol];
        }

        return $symbol;
    }
}
