<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
