<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    public function __construct(private AccountRepository $accountRepository) { }

    #[Route('/compte/{slug}', name: 'accountSlug')]
    public function getOneAccountBySlug(string $slug): Response
    {
        if ($this->getUser()) {
            /**@var Account $user */
            $user = $this->getUser(); // Je récupère l'utilisateur
            $userSlug = $user->getSlug(); // Je récupère le slug de l'utilisateur

            if ($slug !== $userSlug) {
                return $this->redirectToRoute('app_home');
            }
        } else {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('account/show.html.twig', [
            'myAccount' => $this->accountRepository->findOneBy(['slug' => $slug])
        ]);
    }

    #[Route('/comptes', name: 'accounts')]
    public function getAllAccounts()
    {
        return $this->render('account/index.html.twig', [
            'accountEntities' => $this->accountRepository->findBy([], ['createdAt' => 'DESC'])
        ]);
    }
}
