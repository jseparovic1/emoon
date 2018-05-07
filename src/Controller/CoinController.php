<?php

namespace App\Controller;

use App\Command\CoinFetcher;
use App\Repository\CoinRepository;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CoinController extends Controller
{
    /**
     * @param CoinRepository $coinRepository
     * @param Request $request
     * @return Response
     */
    public function index(
        CoinRepository $coinRepository,
        Request $request
    ) {
        /** @var Pagerfanta $coinPaginator */
        $coinPaginator = $coinRepository->createPaginator($coinRepository->findAll(), ['rank' => 'ASC'], 100);
        $coinPaginator->setCurrentPage($request->get('page', 1));

        return $this->render('index.html.twig', [
            'coinsPager' => $coinPaginator
        ]);
    }
}
