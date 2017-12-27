<?php

namespace App\Services;

use App\Entity\Exchange;
use App\Repository\CoinRepository;
use App\Repository\ExchangeRepository;
use App\Services\Exchanges\CoinListingInterface;
use App\Utils\ExchangeNameMapper;

/**
 * Class ExchangeCoinFetcher
 * @package App\Services
 */
class ExchangeCoinFetcher
{
    /** @var array */
    protected $exchanges;

    /**
     * @var ExchangeNameMapper
     */
    private $exchangeNameMapper;

    /**
     * @var ExchangeRepository
     */
    private $exchangeRepository;
    /**
     * @var CoinRepository
     */
    private $coinRepository;

    /**
     * ExchangeCoinFetcher constructor.
     * @param ExchangeNameMapper $exchangeNameMapper
     * @param ExchangeRepository $exchangeRepository
     * @param CoinRepository $coinRepository
     * @param array ...$exchanges
     */
    public function __construct(
        ExchangeNameMapper $exchangeNameMapper,
        ExchangeRepository $exchangeRepository,
        CoinRepository $coinRepository,
        ...$exchanges
    ) {
        $this->exchangeNameMapper = $exchangeNameMapper;
        $this->exchangeRepository = $exchangeRepository;
        $this->coinRepository = $coinRepository;

        foreach ($exchanges as $exchange) {
            $this->exchanges[] = $exchange;
        }
    }

    public function updateCoins()
    {
        /** @var CoinListingInterface $exchangeFetcher */
        foreach ($this->exchanges as $exchangeFetcher) {
            $coins = $exchangeFetcher->getCoinList();
            $storedCoins = $this->coinRepository->findBySymbol($coins);

            $exchangeName = $this->exchangeNameMapper->getExchangeName($exchangeFetcher);

            /** @var Exchange $exchange */
            $exchange = $this->exchangeRepository->findOneBy(['name' => $exchangeName]);
            foreach ($storedCoins as $coin) {
                $exchange->addCoin($coin);
                $this->exchangeRepository->add($exchange);
            }
        }
    }
}
