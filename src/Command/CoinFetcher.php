<?php

namespace App\Command;

use App\Entity\Coin;
use App\Repository\CoinRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CoinFetcher.
 */
class CoinFetcher extends Command
{
    /**
     * @var CoinRepository
     */
    private $coinRepository;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        CoinRepository $coinRepository,
        Client $client,
        EntityManagerInterface $entityManager
    ) {
        $this->coinRepository = $coinRepository;
        $this->client = $client;
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:coins:load')
            ->setDescription('Loads coins from crypto compare');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = 0;
        $limit = 500;

        $output->writeln('<info>Starting coin import, please brace yourself</info>');
        $output->writeln("<info>Limit {$limit}</info>");

        while (true) {
            $output->writeln("<info>Start {$start}</info>");
            $response = $this->client->get(sprintf('/v2/ticker?start=%s&limit=%s', $start, $limit));

            if ($response->getStatusCode() == Response::HTTP_NOT_FOUND) {
                return;
            }

            $content = json_decode($response->getBody()->getContents());

            $this->saveCoinData($content->data, $output);
            $start += $limit;
        }

        $output->writeln('<success>Go celebrate, this is done!</success>');
    }

    protected function saveCoinData($data, $output)
    {
        foreach ($data as $coinData) {
            $coin = $this->coinRepository->findOneBy([
                'name' => $coinData->name,
                'symbol' => $coinData->symbol
            ]);

            if (!$coin instanceof Coin) {
                $coin = new Coin();
                $coin->setName($coinData->name);
                $coin->setSymbol($coinData->symbol);
                $coin->setRank($coinData->rank ?? null);
                $coin->setCanonicalId($coinData->id);
                $coin->setPriceUsd($coinData->quotes->USD->price);
                $this->entityManager->persist($coin);

                $output->writeln("<info>Saving {$coin->getName()}</info>");
            }

            $output->writeln("<info>Updating rank {$coin->getName()} OLD:{$coin->getRank()} NEW:{$coinData->rank}</info>");

            $coin->setRank($coinData->rank);
            $coin->setPriceUsd($coinData->quotes->USD->price);
            $this->entityManager->persist($coin);
        }

        $this->entityManager->flush();
        $this->entityManager->clear();
    }
}
