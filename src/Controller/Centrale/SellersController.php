<?php

namespace App\Controller\Centrale;

use App\Repository\Centrale\SellersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SellersController extends AbstractController
{

    public function __construct(private SellersRepository $sellersRepository)
    {
    }

    #[Route('/sellers', name: 'app_sellers')]
    public function index(): Response
    {
        return $this->render('sellers/index.html.twig', [
            'sellers' => $this->sellersRepository->findAll(),
        ]);
    }
}
