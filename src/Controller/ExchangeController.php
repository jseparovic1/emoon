<?php

namespace App\Controller;

use App\Repository\ExchangeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ExchangeController extends Controller
{
    /**
     * @param ExchangeRepository $exchangeRepository
     * @return Response
     */
    public function index(ExchangeRepository $exchangeRepository)
    {
        return $this->render('index.html.twig',
            ['exchanges' => $exchangeRepository->findAll()]
        );
    }
}
