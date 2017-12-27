<?php

namespace App\Services\Exchanges;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CoinBase
 * @package App\Services\Exchanges
 */
class CoinBase extends BaseExchange implements CoinListingInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var bool
     */
    protected $needsNormalization = true;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getCoinList(): ?array
    {
        $response = $this->client->get('currencies');

        if ($response->getStatusCode() == Response::HTTP_OK) {
            $data = json_decode($response->getBody()->getContents());

            if ($this->needsNormalization()) {
                $data = $this->normalize($data);
            }

            return $data;
        }

        return [];
    }

    protected function normalize(array $data)
    {
        $data = array_filter($data, function ($coin) {
            if (!in_array($coin->id, ['EUR', 'GBP', 'USD'])) {
                return $coin;
            }
        });

        $coins = [];
        foreach ($data as $coin) {
            $coins[] = $coin->id;
        }

        return $coins;
    }
}
