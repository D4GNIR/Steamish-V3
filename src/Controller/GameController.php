<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    // Constructeur
    public function __construct(private GameRepository $gameRepository) { }

    // Récupérer tous les jeux
    #[Route('/jeux', name: 'games')]
    public function getAllGames()
    {
        return $this->render('game/index.html.twig', [
            'gameEntities' => $this->gameRepository->findAll()
        ]);
    }

    // Récupérer un jeu détaillé avec son slug
    #[Route('/jeu/{slug}', name: 'gameSlug')]
    public function getOneGameByName(string $slug): Response
    {
        $myGame = $this->gameRepository->getALotOfThings($slug);

        return $this->render('game/show.html.twig', [
            'myGame' => $myGame,
            'myOthersGames' => $this->gameRepository->getRelatedGames($myGame)
        ]);
    }

    // Afficher une page qui contient tous les jeux d'un genre en particulier
    #[Route('/jeux/genre/{slug}', name: 'gamesGenre')]
    public function getGamesFromOneGenre(string $slug)
    {
        return $this->render('game/genre.html.twig', [
            'genreGames' => $this->gameRepository->getGamesOfOneGenre($slug)
        ]);
    }

    // // Afficher une page qui contient tous les jeux avec une langue en particulier
    // #[Route('/jeux/langue/{slug}', name: 'gamesLanguage')]
    // public function getGamesOfOneLanguage(string $slug)
    // {
    //     return $this->render('game/language.html.twig', [
    //         'myGame' => $this->gameRepository->getALotOfThings($slug)
    //     ]);
    // }
}
