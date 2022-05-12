<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Topic;
use App\Form\AddMessageType;
use App\Form\AddTopicType;
use App\Repository\AccountRepository;
use App\Repository\ForumRepository;
use App\Repository\TopicRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends AbstractController
{
    private TopicRepository $topicRepository;
    private ForumRepository $forumRepository;
    private AccountRepository $accountRepository;
    private EntityManagerInterface $em;


    public function __construct(
        TopicRepository $topicRepository,
        EntityManagerInterface $em,
        ForumRepository $forumRepository,
        AccountRepository $accountRepository
    ) {
        $this->topicRepository = $topicRepository;
        $this->em = $em;
        $this->forumRepository = $forumRepository;
        $this->accountRepository = $accountRepository;
    }

    #[Route('/topic/{idTopic}', name: 'app_topic')]
    public function index($idTopic,TopicRepository $topicRepository,Request $request,EntityManagerInterface $em): Response
    {
        $messageEntity = new Message();
        $user = $this->getUser();
        $topicEntity= $topicRepository->find($idTopic);
        $form = $this->createForm(AddMessageType::class, $messageEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageEntity->setTopic($topicEntity);
            $messageEntity->setCreatedAt(new DateTime('now'));
            $messageEntity->setCreatedBy($user);

            $em->persist($messageEntity);
            $em->flush();

            return $this->redirectToRoute('app_topic',[
                'idTopic' => $idTopic,
            ]);
        }
        return $this->render('topic/index.html.twig', [
            'topic' => $topicEntity,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/topic/create/{idForum}', name: 'app_topic_create')]
    public function topicCreate($idForum,Request $request,EntityManagerInterface $em,ForumRepository $forumRepository): Response
    {
        $topicEntity = new Topic();
        $user = $this->getUser();
        $idForum = $forumRepository->find($idForum);
        $form = $this->createForm(AddTopicType::class, $topicEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $topicEntity->setForum($idForum);
            $topicEntity->setCreatedAt(new DateTime('now'));
            $topicEntity->setCreatedBy($user);

            $em->persist($topicEntity);
            $em->flush();

            return $this->redirectToRoute('app_topic',[
                'idTopic' => $topicEntity->getId(),
            ]);
        }

        return $this->render('topic/addTopic.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/topic/edit/{idForum}/{idTopic}', name: 'app_topic_edit')]
    public function topicEdit($idForum,$idTopic,Request $request,EntityManagerInterface $em): Response
    {
        $topic = $this->topicRepository->find($idTopic);
        $user = $this->getUser();
        $idForum = $this->forumRepository->find($idForum);
        $form = $this->createForm(AddMessageType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $topic->setForum($idForum);
            $topic->setCreatedAt(new DateTime('now'));
            $topic->setCreatedBy($user);

            $em->persist($topic);
            $em->flush();

            return $this->redirectToRoute('app_topic',[
                'idTopic' => $topic->getId(),
            ]);
        }

        return $this->render('topic/addTopic.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/topic/delete/{idTopic}', name: 'app_topic_delete')]
    public function deleteOkGenre($idTopic, Request $request, EntityManagerInterface $em): Response
    {
        $topic = $this->topicRepository->find($idTopic);
        $em->remove($topic);
        $em->flush();
        return $this->redirectToRoute('app_forum');

    }

}
