<?php

namespace App\Controller;

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
}
