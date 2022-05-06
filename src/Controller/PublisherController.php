<?php

namespace App\Controller;

use App\Repository\PublisherRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/editeur')]
class PublisherController extends AbstractController
{
    public function __construct(private PublisherRepository $publisherRepository)
    {
    }

    #[Route('/', name: 'app_publisher')]
    public function index(): Response
    {
        return $this->render('publisher/index.html.twig', [
            'publishers' => $this->publisherRepository->getPublishersAll()
        ]);
    }

    #[Route('/{slug}', name: 'app_publisher_show')]
    public function show(string $slug): Response
    {
        return $this->render('publisher/show.html.twig', [
            'publisher' => $this->publisherRepository->findPublisher($slug)
        ]);
    }

}
