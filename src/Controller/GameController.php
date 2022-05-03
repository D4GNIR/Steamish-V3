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
        return $this->render('game/games.html.twig', [
            'gameEntities' => $this->gameRepository->findAll()
        ]);
    }

    // Récupérer un jeu avec son ID
    #[Route('/jeu/{id}', name: 'gameId')]
    public function getOneGameById($id): Response
    {
        return $this->render('game/game.html.twig', [
            'myGame' => $this->gameRepository->find($id)
        ]);
    }

    // Récupérer un jeu avec son ID
    #[Route('/{name}', name: 'gameName')]
    public function getOneGameByName($name): Response
    {
        return $this->render('game/game.html.twig', [
            'myGame' => $this->gameRepository->find($name)
        ]);
    }

    // #[Route('/game', name: 'app_game')]
    // public function index(): Response
    // {
    //     return $this->render('game/game.html.twig', [
    //         'controller_name' => 'GameController',
    //     ]);
    // }
}
