<?php

namespace App\Services\Exchanges;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Bittrex
 * @package App\Services\Exchanges
 */
class Bittrex implements CoinListingInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getCoinList(): ?array
    {
        $response = $this->client->get('/api/v1.1/public/getcurrencies');

        if ($response->getStatusCode() == Response::HTTP_OK) {
            $data = json_decode($response->getBody()->getContents());

            return $this->normalize($data->result);
        }

        return [];
    }

    protected function normalize(array $data)
    {
        $coins = [];
        foreach ($data as $coin) {
            $coins[] = $coin->Currency;
        }

        return $coins;
    }
}
