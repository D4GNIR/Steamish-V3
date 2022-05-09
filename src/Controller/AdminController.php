<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/dashboard/genre', name: 'app_adminGenre')]
    public function indexGenre(GenreRepository $genreRepository): Response
    {
        $genre = $genreRepository->findAll();
        return $this->render('admin/indexGenre.html.twig', [
            'genres' => $genre
        ]);
    }

    #[Route('/admin/dashboard/genre/show/{slug}', name: 'app_genre_show')]
    public function showGenre(GenreRepository $genreRepository): Response
    {
        $genre = $genreRepository->findAll();
        return $this->render('admin/indexGenre.html.twig', [
            'genres' => $genre
        ]);
    }

    #[Route('/admin/dashboard/genre/edit/{slug}', name: 'app_genre_edit')]
    public function editGenre(GenreRepository $genreRepository): Response
    {
        $genre = $genreRepository->findAll();
        return $this->render('admin/indexGenre.html.twig', [
            'genres' => $genre
        ]);
    }

    #[Route('/admin/dashboard/genre/delete/{slug}', name: 'app_genre_delete')]
    public function deleteGenre(GenreRepository $genreRepository): Response
    {
        $genre = $genreRepository->findAll();
        return $this->render('admin/indexGenre.html.twig', [
            'genres' => $genre
        ]);
    }
}
