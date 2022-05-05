<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    public function __construct(private AccountRepository $accountRepository) { }

    #[Route('/account/{slug}', name: 'accountSlug')]
    public function getOneGameByName(string $slug, AccountRepository $accountRepository): Response
    {
        return $this->render('account/index.html.twig', [
            'myAccount' => $this->accountRepository->findOneBy(['slug' => $slug])
        ]);
    }
}
