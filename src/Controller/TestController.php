<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\PublisherType;
use App\Repository\AccountRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    private AccountRepository $accountRepository;
    private PaginatorInterface $paginator;

        // Constructeur
        public function __construct(
            AccountRepository $accountRepository,
            PaginatorInterface $paginator
        ) {
            $this->accountRepository = $accountRepository;
            $this->paginator = $paginator;
         }

    #[Route('/test', name: 'app_test')]
    public function index(Request $request): Response
    {
        $qb = $this->accountRepository->getQbAll();

        $pagination = $this->paginator->paginate(
            $qb, //La query
            $request->query->getInt('page',1), //Le numero de page de depart
            15 //Le nombre de résultat pas page
        );

        // $publisherEntity = new Publisher();
        // $form = $this->createForm(PublisherType::class, $publisherEntity);

        // // Cette ligne permet le lien entre formulaire et entité si le formulaire est lié à l'entité
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $publisherEntity->setCreatedAt(new \DateTime());
        //     dump($publisherEntity);
        // }

        // $user = $this->getUser();
        // dump($user);

        return $this->render('test/index.html.twig', [
            // 'controller_name' => 'TestController',
            'pagination' => $pagination,
            
        ]);
    }
}
