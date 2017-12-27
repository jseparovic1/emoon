<?php

namespace App\Services;

use App\Entity\Exchange;
use App\Repository\CoinRepository;
use App\Repository\ExchangeRepository;
use App\Services\Exchanges\CoinListingInterface;
use App\Utils\ExchangeMapper;

/**
 * Class ExchangeCoinFetcher
 * @package App\Services
 */
class ExchangeCoinFetcher
{
    /**
     * @var ExchangeRepository
     */
    private $exchangeRepository;

    /**
     * @var CoinRepository
     */
    private $coinRepository;

    /**
     * @var ExchangeMapper
     */
    private $exchangeMapper;

    /**
     * ExchangeCoinFetcher constructor.
     * @param ExchangeMapper $exchangeMapper
     * @param ExchangeRepository $exchangeRepository
     * @param CoinRepository $coinRepository
     */
    public function __construct(
        ExchangeMapper $exchangeMapper,
        ExchangeRepository $exchangeRepository,
        CoinRepository $coinRepository
    ) {
        $this->exchangeRepository = $exchangeRepository;
        $this->coinRepository = $coinRepository;
        $this->exchangeMapper = $exchangeMapper;
    }

    /**
     * Update coin list for each exchange
     */
    public function updateCoins()
    {
        /** @var CoinListingInterface $exchangeFetcher */
        foreach ($this->exchangeMapper->getExchanges() as $exchangeFetcher) {
            $coins = $exchangeFetcher->getCoinList();
            $exchangeName = $this->exchangeMapper->getExchangeName($exchangeFetcher);

            /** @var Exchange $exchange */
            $exchange = $this->exchangeRepository->findOneBy(['name' => $exchangeName]);

            //Finds array of coins that are stored in exchange
            $exchangeStoredCoins = $this->exchangeRepository->findAllCoins($exchange->getId());

            //if there is new coin in response it will be returned here
            $newCoins = $this->getChangedCoins($coins, $exchangeStoredCoins);
            if ($newCoins) {
                $this->addCoinsToExchange($exchange, $newCoins);
            }

            //If there is coin in database that is not retried from response it will be returned
            $removedCoins = $this->getChangedCoins($exchangeStoredCoins, $coins);
            if ($removedCoins) {
                $this->removeCoinsFromExchange($exchange, $removedCoins);
            }
        }
    }

    /**
     * Calculates array dif and gets coins objects from database
     * @param $coins
     * @param $storedCoins
     * @return array|mixed
     */
    public function getChangedCoins($coins, $storedCoins)
    {
        $newCoins = array_diff($coins, $storedCoins);
        if (empty($newCoins)) {
            return [];
        }

        return $this->coinRepository->findBySymbol($newCoins);
    }

    /**
     * Adds relation between exchange and coin
     * @param Exchange $exchange
     * @param $coins
     */
    private function addCoinsToExchange(Exchange $exchange, $coins)
    {
        foreach ($coins as $coin) {
            $exchange->addCoin($coin);
            $this->exchangeRepository->add($exchange);
        }
    }

    /**
     * Removes relation between exchange and coin
     * @param Exchange $exchange
     * @param $coins
     */
    private function removeCoinsFromExchange(Exchange $exchange, $coins)
    {
        foreach ($coins as $coin) {
            $exchange->removeCoin($coin);
            $this->exchangeRepository->add($coin);
        }
    }
}
