<?php

namespace App\Controller;

use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends AbstractController
{
    private TopicRepository $topicRepository;
    private EntityManagerInterface $em;


    public function __construct(
        TopicRepository $topicRepository,
    ) {
        $this->topicRepository = $topicRepository;
    }

    #[Route('/topic', name: 'app_topic')]
    public function index(): Response
    {
        $topicEntity = $this->topicRepository->findBy();
        return $this->render('topic/index.html.twig', [
            'topic' => $topicEntity,
        ]);
    }
}
