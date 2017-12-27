<?php

namespace App\Services\Exchanges;

/**
 * Class AbstractExchange
 * @package App\Services\Exchanges
 */
abstract class BaseExchange
{
    protected $needsNormalization = false;

    protected function needsNormalization(): bool
    {
        return $this->needsNormalization;
    }
}
