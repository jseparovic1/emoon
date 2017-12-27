<?php

namespace App\Controller;

use App\Services\ExchangeCoinFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ExchangeController extends Controller
{
    /**
     * @param ExchangeCoinFetcher $coinBase
     * @return Response
     */
    public function index(ExchangeCoinFetcher $coinBase)
    {
        dump($coinBase->updateCoins());
        return new Response("hoho");
    }
}
