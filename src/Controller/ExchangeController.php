<?php

namespace App\Controller;

use App\Entity\Exchange;
use App\Repository\ExchangeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ExchangeController extends Controller
{
    /**
     * @var ExchangeRepository
     */
    private $exchangeRepository;

    public function __construct(ExchangeRepository $exchangeRepository)
    {
        $this->exchangeRepository = $exchangeRepository;
    }

    public function index(): Response
    {
        $exchanges = $this->exchangeRepository->findAll();
        return $this->render('exchange/index.html.twig', compact('exchanges', $exchanges));
    }

    public function show(string $id): Response
    {
        $exchange = $this->exchangeRepository->findOneBy(['id' => $id]);

        if (!$exchange instanceof Exchange) {
            $this->createNotFoundException('Exchange not found!');
        }

        return $this->render('exchange/show.html.twig', [
            'exchange' => $exchange
        ]);
    }
}
