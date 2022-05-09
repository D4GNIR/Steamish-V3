<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\AddGenreType;
use App\Repository\GameRepository;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;

class AdminGenreController extends AbstractController
{
    public function __construct(
        private GenreRepository $genreRepository,
        private GameRepository $gameRepository,
    ) { }

    #[Route('/admin/genres_liste', name: 'app_adminGenre')]
    public function indexGenre(GenreRepository $genreRepository): Response
    {
        $genre = $genreRepository->findAll();
        return $this->render('admin_genre/index.html.twig', [
            'genres' => $genre
        ]);
    }

    #[Route('/admin/genres/show/{slug}', name: 'app_genre_show')]
    public function showGenre(string $slug,GenreRepository $genreRepository,GameRepository $gameRepository): Response
    {
        $genre = $genreRepository->findOneBy(['slug' => $slug]);
        return $this->render('admin_genre/show.html.twig', [
            'genreGames' => $this->gameRepository->getGamesOfOneGenre($slug),
            'genre' => $genre
        ]);
    }

    #[Route('/admin/genres/create', name: 'app_genre_create')]
    public function addGenre(GenreRepository $genreRepository,Request $request,EntityManagerInterface $em): Response
    {
        $genreEntity = new Genre();
        $form = $this->createForm(AddGenreType::class, $genreEntity);

        // Cette ligne permet le lien entre formulaire et entité si le formulaire est lié à l'entité
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genreEntity->setSlug((new Slugify())->slugify($genreEntity->getName()));
            $em->persist($genreEntity);
            $em->flush();
            return $this->redirectToRoute('app_adminGenre');
        }

        return $this->render('admin_genre/addGenre.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/genres/edit/{slug}', name: 'app_genre_edit')]
    public function editGenre(Genre $genre, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AddGenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $genre->setSlug((new Slugify())->slugify($genre->getName()));

            $em->flush();
            return $this->redirectToRoute('app_adminGenre');
        }

        return $this->render('admin_genre/addGenre.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/genres/delete/{slug}', name: 'app_genre_delete')]
    public function deleteGenre(Genre $genre, Request $request, EntityManagerInterface $em): Response
    {

        return $this->render('admin_genre/delete.html.twig', [
            'genre' => $genre
        ]);
    }

    #[Route('/admin/genres/delete/ok/{slug}', name: 'app_genre_ok_delete')]
    public function deleteOkGenre(Genre $genre, Request $request, EntityManagerInterface $em): Response
    {

            $em->remove($genre);
            $em->flush();
            return $this->redirectToRoute('app_adminGenre');

    }
}
