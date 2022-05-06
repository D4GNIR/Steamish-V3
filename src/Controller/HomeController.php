<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\CommentRepository;
use App\Repository\GameRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Cocur\Slugify\Slugify;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        GameRepository $gameRepository, CommentRepository $commentRepository
    ): Response
    {
        return $this->render('home/index.html.twig', [
            'lastPublishedGames' => $gameRepository->findBy([], ['publishedAt' => 'DESC'], 9),
            'mostPlayedGames' => $gameRepository->getMostGameByOrderBy('SUM(lib.gameTime)'),
            'lastComments' => $commentRepository->findBy([], ['createdAt' => 'DESC'], 4),
            'mostBoughtGames' => $gameRepository->getMostGameByOrderBy('COUNT(lib.game)'),
        ]);
    }

    // Récupérer un jeu avec son slug
    #[Route('/jeu/{slug}', name: 'commentGameSlug')]
    public function getOneGameByName(string $slug): Response
    {
        return $this->render('game/show.html.twig', [
            'myGame' => $this->gameRepository->findOneBy([' comment.game.slug' => $slug])
        ]);
    }

    #[Route('/inscription', name: 'inscription')]
    public function connect(Request $request, EntityManagerInterface $em):Response
    {
        $form = $this->createForm(AccountType::class, new Account());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Account $account */
            $account = $form->getData();
            $account->setCreatedAt(new DateTime('now'));
            $account->setSlug((new Slugify())->slugify($account->getName()));            
            
            $em->persist($account);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('common/_connect.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifier/{slug}', name: 'modification')]
    public function edit(Account $account, Request $request, EntityManagerInterface $em):Response
    {
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
                /** @var Account $account */                
                $account->setSlug((new Slugify())->slugify($account->getName()));
                
                $em->flush();
                return $this->redirectToRoute('app_home');
            }

        return $this->render('common/_modify.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
