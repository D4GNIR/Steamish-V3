<?php

namespace App\Controller;

use App\Entity\Publisher;
use App\Form\AddPublisherType;
use App\Repository\GameRepository;
use App\Repository\PublisherRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPublisherController extends AbstractController
{
    public function __construct(
        private PublisherRepository $publisherRepository,
        private GameRepository $gameRepository,
    ) { }

    // Voir la liste des éditeurs
    #[Route('/admin/editeurs_liste', name: 'app_adminPublisher')]
    public function indexPublisher(PublisherRepository $publisherRepository): Response
    {
        $publisher = $publisherRepository->findAll();
        return $this->render('admin_publisher/index.html.twig', [
            'publishers' => $publisher
        ]);
    }

    // Voir le détail d'un éditeur
    #[Route('/admin/editeurs/show/{slug}', name: 'app_admin_publisher_show')]
    public function showPublisher(
        string $slug,
        PublisherRepository $publisherRepository
    ): Response
    {
        $publisher = $publisherRepository->findOneBy(['slug' => $slug]);
        return $this->render('admin_publisher/show.html.twig', [
            'publisher' => $publisher
        ]);
    }

    // Rajouter un éditeur
    #[Route('/admin/editeurs/create', name: 'app_admin_publisher_create')]
    public function addGenre(Request $request, EntityManagerInterface $em): Response
    {
        $publisherEntity = new Publisher();
        $form = $this->createForm(AddPublisherType::class, $publisherEntity);

        // Cette ligne permet le lien entre formulaire et entité si le formulaire est lié à l'entité
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publisherEntity->setSlug((new Slugify())->slugify($publisherEntity->getName()));
            $em->persist($publisherEntity);
            $em->flush();
            return $this->redirectToRoute('app_adminPublisher');
        }

        return $this->render('admin_publisher/addPublisher.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Modifier un éditeur
    #[Route('/admin/editeurs/edit/{slug}', name: 'app_admin_publisher_edit')]
    public function editPublisher(Publisher $publisher, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AddPublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publisher->setSlug((new Slugify())->slugify($publisher->getName()));

            $em->flush();
            return $this->redirectToRoute('app_adminPublisher');
        }

        return $this->render('admin_publisher/addPublisher.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Supprimer un éditeur
    #[Route('/admin/editeurs/delete/{slug}', name: 'app_admin_publisher_delete')]
    public function deletePublisher(Publisher $publisher): Response
    {
        return $this->render('admin_publisher/delete.html.twig', [
            'publisher' => $publisher
        ]);
    }

    // Confirmation de suppression
    #[Route('/admin/editeurs/delete/ok/{slug}', name: 'app_publisher_ok_delete')]
    public function deleteOkPublisher(Publisher $publisher, Request $request, EntityManagerInterface $em): Response
    {
        $em->remove($publisher);
        $em->flush();
        return $this->redirectToRoute('app_adminPublisher');
    }

    // #[Route('/admin/publisher', name: 'app_admin_publisher')]
    // public function index(): Response
    // {
    //     return $this->render('admin_publisher/index.html.twig', [
    //         'controller_name' => 'AdminPublisherController',
    //     ]);
    // }
}
