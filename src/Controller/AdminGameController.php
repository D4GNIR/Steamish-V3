<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\AddGameType;
use App\Repository\GameRepository;
use App\Repository\LibraryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminGameController extends AbstractController
{
    private GameRepository $gameRepository;
    private PaginatorInterface $paginator;
    private SluggerInterface $sluger;
    private LibraryRepository $libraryRepository;

    public function __construct(
        GameRepository $gameRepository,
        PaginatorInterface $paginator,
        SluggerInterface $sluger,
        LibraryRepository $libraryRepository
    ) {
        $this->gameRepository = $gameRepository;
        $this->paginator = $paginator;
        $this->sluger = $sluger;
        $this->libraryRepository = $libraryRepository;
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

    #[Route('/admin/games/create', name: 'app_game_create')]
    public function addGame(GameRepository $gameRepository,Request $request,EntityManagerInterface $em): Response
    {
        $gameEntity = new Game();
        $form = $this->createForm(AddGameType::class, $gameEntity);

        // Cette ligne permet le lien entre formulaire et entité si le formulaire est lié à l'entité
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->sluger->slug($gameEntity->getName());
            $gameEntity->setSlug($slug);
            $em->persist($gameEntity);
            $em->flush();
            return $this->redirectToRoute('app_admin_game');
        }

        return $this->render('admin_game/addGame.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/games/edit/{slug}', name: 'app_game_edit')]
    public function editGenre(Game $game, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AddGameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $this->sluger->slug($game->getName());
            $game->setSlug($slug);

            $em->flush();
            return $this->redirectToRoute('app_admin_game');
        }

        return $this->render('admin_game/addGame.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/games/delete/{slug}', name: 'app_game_delete')]
    public function deleteGame($slug, Request $request, EntityManagerInterface $em): Response
    {

        $gameEntity = $this->gameRepository->findOneBy(['slug' => $slug]);
        $librariesEntities = $this->libraryRepository->getLibraryByGame($gameEntity);

        foreach ($librariesEntities as $library) {
            $em->remove($library);
        }
        $em->flush();

        
        $em->remove($gameEntity);
        $em->flush();
        return $this->redirectToRoute('app_admin_game');

    }
}
