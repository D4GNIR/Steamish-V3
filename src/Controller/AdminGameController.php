<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminGameController extends AbstractController
{
    public function __construct(
        private GameRepository $gameRepository,
        private PaginatorInterface $paginator
    ) {
        $this->gameRepository = $gameRepository;
        $this->paginator = $paginator;
     }

    #[Route('/admin/game_liste', name: 'app_admin_game')]
    public function indexGame(Request $request): Response
    {
        // $games = $this->gameRepository->findAll();
        $qb = $this->gameRepository->getQbAll();

        $pagination = $this->paginator->paginate(
            $qb, //La query
            $request->query->getInt('page',1), //Le numero de page de depart
            15 //Le nombre de résultat pas page
        );

        return $this->render('admin_game/index.html.twig', [
            // 'games' => $games,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/admin/game/show/{slug}', name: 'app_game_show')]
    public function showGame(string $slug,GameRepository $gameRepository): Response
    {
        $game = $this->gameRepository->getALotOfThings($slug);
 
        return $this->render('admin_game/show.html.twig', [
            'game' => $game
        ]);
    }
}
