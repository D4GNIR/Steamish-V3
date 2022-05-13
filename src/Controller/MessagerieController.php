<?php

namespace App\Controller;

use App\Entity\DirectMessage;
use App\Form\SendMessageType;
use App\Repository\AccountRepository;
use App\Repository\DirectMessageRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagerieController extends AbstractController
{
    private AccountRepository $accountRepository;
    private DirectMessageRepository $directMessageRepository;
    private EntityManagerInterface $em;

    public function __construct(
        AccountRepository $accountRepository,
        DirectMessageRepository $directMessageRepository,
        EntityManagerInterface $em
  
    ) {
        $this->accountRepository = $accountRepository;
        $this->directMessageRepository = $directMessageRepository;
        $this->em = $em;
    }

    #[Route('/messagerie/{idUser}', name: 'app_messagerie')]
    public function index($idUser,Request $request): Response
    {
        $slug = $this->accountRepository->find($idUser);

        $directMessageEntity = new DirectMessage();
        $user = $this->getUser();
        $form = $this->createForm(SendMessageType::class, $directMessageEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $directMessageEntity->setCreatedAt(new DateTime('now'));
            $directMessageEntity->setCreatedBy($user);

            $this->em->persist($directMessageEntity);
            $this->em->flush();
        }

        return $this->render('messagerie/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
