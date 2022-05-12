<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Form\AddForumType;
use App\Repository\ForumRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\New_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    private ForumRepository $forumRepository;
    private EntityManagerInterface $em;


    public function __construct(
        ForumRepository $forumRepository,
    ) {
        $this->forumRepository = $forumRepository;
    }

    #[Route('/forum', name: 'app_forum')]
    public function index(): Response
    {
        $forumEntity = $this->forumRepository->findAll();
        return $this->render('forum/index.html.twig', [
            'forumEntity' => $forumEntity,
        ]);
    }

    #[Route('/forum/create', name: 'app_forum_create')]
    public function forumCreate(Request $request,EntityManagerInterface $em): Response
    {
        $forumEntity = new Forum();
        $form = $this->createForm(AddForumType::class, $forumEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $forumEntity->setCreatedAt(new DateTime('now'));
            $em->persist($forumEntity);
            $em->flush();
            return $this->redirectToRoute('app_forum');
        }

        return $this->render('forum/addForum.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/forum/show/forum/{id}', name: 'app_forum_show')]
    public function showForum(string $id, ForumRepository $forumRepository): Response
    {
        $forum = $forumRepository->findOneBy(['id' => $id]);
        return $this->render('forum/show.html.twig', [
            'forum' => $forum
        ]);
    }

    #[Route('/forum/edit/{id}', name: 'app_forum_edit')]
    public function editGenre(Forum $forum, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(AddForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($forum);
            $em->flush();
            return $this->redirectToRoute('app_forum');
        }
        

        return $this->render('forum/addForum.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/forum/delete/{id}', name: 'app_forum_delete')]
    public function deleteForum(Forum $forum, Request $request, EntityManagerInterface $em): Response
    {

            $em->remove($forum);
            $em->flush();
            return $this->redirectToRoute('app_forum');

    }
    
}
