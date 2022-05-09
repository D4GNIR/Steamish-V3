<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(Request $request): Response
    {
        $publisherEntity = new Publisher();
        $form = $this->createForm(PublisherType::class, $publisherEntity);

        // Cette ligne permet le lien entre formulaire et entité si le formulaire est lié à l'entité
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publisherEntity->setCreatedAt(new \DateTime());
            dump($publisherEntity);
        }

        $user = $this->getUser();
        dump($user);

        return $this->render('test/index.html.twig', [
            // 'controller_name' => 'TestController',
            'form' => $form->createView()
        ]);
    }
}
