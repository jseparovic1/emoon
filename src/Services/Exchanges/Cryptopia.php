<?php

namespace App\Services\Exchanges;

use App\Services\Exchanges\Exchange as BaseExchange;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\HttpFoundation\Response;

class Cryptopia extends BaseExchange implements CoinListingInterface
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
        $response = $this->client->get('/api/GetCurrencies');

        if ($response->getStatusCode() == Response::HTTP_OK) {
            $data = json_decode($response->getBody()->getContents());

            return $this->normalize($data->Data);
        }

        return [];
    }

    protected function normalize(array $data)
    {
        $coins = [];
        foreach ($data as $coin) {
            if ($coin->ListingStatus == 'Active' && $coin->Status == 'OK') {
                $coins[] = $this->getNormalizedCoinSymbol($coin->Symbol);
            }
        }

        return $coins;
    }
}
