<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\AddCommentType;
use App\Form\FilterGamesType;
use App\Repository\CommentRepository;
use App\Repository\CountryRepository;
use App\Repository\GameRepository;
use App\Repository\GenreRepository;
use App\Repository\PublisherRepository;
use Cocur\Slugify\Slugify;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    // Constructeur
    public function __construct(
        private GameRepository $gameRepository,
        private CommentRepository $commentRepository,
    ) {
        $this->gameRepository = $gameRepository;
        $this->commentRepository = $commentRepository;
    }

    // Récupérer tous les jeux
    #[Route('/jeux', name: 'games')]
    public function getAllGames(Request $request, GameRepository $gameRepository, PaginatorInterface $paginator)
    {
        $qb = $gameRepository->getQbAll();

        $formFilter = $this->createForm(FilterGamesType::class);
        $formFilter->handleRequest($request);

        if ($formFilter->isSubmitted() && $formFilter->isValid()) {
            $qb = $this->gameRepository->updateQbByData($qb, $formFilter->getData());  
        }


        $pagination = $paginator->paginate(
            $qb,
            $request->query->getInt('page', 1),
            10
        );


        return $this->render('game/index.html.twig', [
            // 'gameEntities' => $this->gameRepository->findAll(),
            'form' => $formFilter->createView(),
            'pagination' => $pagination,
        ]);
    }

    // Récupérer un jeu détaillé avec son slug
    #[Route('/jeu/{slug}', name: 'gameSlug')]
    public function getOneGameByName(
        string $slug,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $myGame = $this->gameRepository->getALotOfThings($slug);
        $gameEntity = $this->gameRepository->findOneBy(['slug' => $slug]);
        $user = $this->getUser();

        dump($myGame);
        dump($user);
        dump($gameEntity);

        $myOtherGames = $this->gameRepository->getRelatedGames($myGame);
        
        if ($user != null) {
            $myComment = $this->commentRepository->getCommentByAccountAndByGame($user, $gameEntity);
        } else {
            $myComment = null;
        }

        // DEBUT FORMULAIRE COMMENTAIRE
        $commentForm = $this->createForm(AddCommentType::class, new Comment());
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            /** @var Comment $comment */
            $comment = $commentForm->getData();
            $comment->setCreatedAt(new DateTime('now'));
            $comment->setAccount($user);
            $comment->setGame($gameEntity);
            
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('gameSlug', (['slug' => $slug]));
        }
        // FIN FORMULAIRE COMMENTAIRE

        return $this->render('game/show.html.twig', [
            'myGame' => $myGame,
            'myOthersGames' => $myOtherGames,
            'comment' => $myComment,
            'commentForm' => $commentForm->createView(),
        ]);
    }

    // Afficher une page qui contient tous les jeux d'un genre en particulier
    #[Route('/jeux/genre/{slug}', name: 'gamesGenre')]
    public function getGamesFromOneGenre(string $slug, GenreRepository $genreRepository)
    {
        $genre = $genreRepository->findOneBy(['slug' => $slug]);

        return $this->render('game/genre.html.twig', [
            'genreGames' => $this->gameRepository->getGamesOfOneGenre($slug),
            'genre' => $genre
        ]);
    }

    // Afficher une page qui contient tous les jeux avec une langue en particulier
    #[Route('/jeux/langue/{slug}', name: 'gamesLanguage')]
    public function getGamesOfOneLanguage(string $slug, CountryRepository $countryRepository)
    {
        $country = $countryRepository->findOneBy(['slug' => $slug]);
        
        return $this->render('game/language.html.twig', [
            'countryGames' => $this->gameRepository->getGamesOfOneLanguage($slug),
            'country' => $country
        ]);
    }
}
