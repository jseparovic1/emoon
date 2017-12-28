<?php

namespace App\Controller;

use App\Repository\ExchangeRepository;
use App\Services\ExchangeCoinFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ExchangeController extends Controller
{
    /**
     * @param ExchangeRepository $exchangeRepository
     * @param ExchangeCoinFetcher $fetcher
     * @return Response
     */
    public function index(
        ExchangeRepository $exchangeRepository,
        ExchangeCoinFetcher $fetcher
    ) {
//        $fetcher->updateCoins();
        return $this->render('index.html.twig',
            ['exchanges' => $exchangeRepository->findAll()]
        );
    }
}
