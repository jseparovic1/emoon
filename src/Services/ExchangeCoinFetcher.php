<?php

namespace App\Services;

use App\Entity\Coin;
use App\Entity\Exchange;
use App\Event\CoinAddedToExchangeEvent;
use App\Repository\CoinRepository;
use App\Repository\ExchangeRepository;
use App\Services\Exchanges\CoinListingInterface;
use App\Utils\EmonEvents;
use App\Utils\ExchangeMapper;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ExchangeCoinFetcher.
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
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * ExchangeCoinFetcher constructor.
     * @param ExchangeMapper           $exchangeMapper
     * @param ExchangeRepository       $exchangeRepository
     * @param CoinRepository           $coinRepository
     * @param EventDispatcherInterface $eventDispatcher
     * @param LoggerInterface          $logger
     */
    public function __construct(
        ExchangeMapper $exchangeMapper,
        ExchangeRepository $exchangeRepository,
        CoinRepository $coinRepository,
        EventDispatcherInterface $eventDispatcher,
        LoggerInterface $logger
    ) {
        $this->exchangeRepository = $exchangeRepository;
        $this->coinRepository = $coinRepository;
        $this->exchangeMapper = $exchangeMapper;
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
    }

    /**
     * Update coin list for each exchange.
     */
    public function updateCoins()
    {
        /** @var CoinListingInterface $exchangeFetcher */
        foreach ($this->exchangeMapper->getExchanges() as $exchangeFetcher) {
            $coinsData = $exchangeFetcher->getCoinList();
            $exchangeName = $this->exchangeMapper->getExchangeName($exchangeFetcher);

            /** @var Exchange $exchange */
            $exchange = $this->exchangeRepository->findOneBy(['name' => $exchangeName]);
            $exchange->setUpdatedAt(new \DateTime('now'));

            //Finds array of coins that are stored in exchange
            $exchangeStoredCoins = $this->exchangeRepository->findAllCoins($exchange->getId());

            //if there is new coin in response it will be returned here
            $newCoins = $this->getChangedCoins($coinsData, $exchangeStoredCoins);
            if ($newCoins) {
                $this->addCoinsToExchange($exchange, $newCoins);
            }

            //If there is coin in database that is not retried from response it will be returned
            $removedCoins = $this->getChangedCoins($exchangeStoredCoins, $coinsData);
            if ($removedCoins) {
                $this->removeCoinsFromExchange($exchange, $removedCoins);
            }
        }
    }

    /**
     * Calculates array dif and gets coins objects from database.
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
     * Adds relation between exchange and coin.
     * @param Exchange $exchange
     * @param $coins
     */
    private function addCoinsToExchange(Exchange $exchange, $coins)
    {
        /** @var Coin $coin */
        foreach ($coins as $coin) {
            $exchange->addCoin($coin);
            $this->exchangeRepository->add($exchange);

            $this->eventDispatcher->dispatch(EmonEvents::COIN_ADDED, new CoinAddedToExchangeEvent($coin, $exchange));
            $this->logger->critical("COIN ADDED TO EXCHANGE : {$coin->getName()}");
        }
    }

    /**
     * Removes relation between exchange and coin.
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
