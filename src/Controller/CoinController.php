<?php

namespace App\Controller;

use App\Repository\CoinRepository;
use App\Repository\ExchangeRepository;
use App\Services\ExchangeCoinFetcher;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CoinController extends Controller
{
    /**
     * @param  ExchangeRepository  $exchangeRepository
     * @param  ExchangeCoinFetcher $fetcher
     * @param  CoinRepository      $coinRepository
     * @param  Request             $request
     * @return Response
     */
    public function index(
        ExchangeRepository $exchangeRepository,
        ExchangeCoinFetcher $fetcher,
        CoinRepository $coinRepository,
        Request $request
    ) {
        $fetcher->updateCoins();

        /** @var Pagerfanta $coinPaginator */
        $coinPaginator = $coinRepository->createPaginator($coinRepository->findAll(), ['rank' => 'ASC'], 100);
        $coinPaginator->setCurrentPage($request->get('page', 1));

        return $this->render('index.html.twig', [
            'exchanges' => $exchangeRepository->findAll(),
            'coinsPager' => $coinPaginator
        ]);
    }
}
