<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Topic;
use App\Form\AddMessageType;
use App\Form\AddTopicType;
use App\Repository\AccountRepository;
use App\Repository\ForumRepository;
use App\Repository\MessageRepository;
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
        AccountRepository $accountRepository,
        MessageRepository $messageRepository
    ) {
        $this->topicRepository = $topicRepository;
        $this->em = $em;
        $this->forumRepository = $forumRepository;
        $this->accountRepository = $accountRepository;
        $this->messageRepository = $messageRepository;
    }

    // Création d'un topic
    #[Route('/forum/{idForum}/topic/create', name: 'app_topic_create')]
    public function topicCreate(
         $idForum,
         Request $request,
         EntityManagerInterface $em,
         ForumRepository $forumRepository
         ): Response
    {
         $topicEntity = new Topic();
         $user = $this->getUser();
         $forumEntity = $forumRepository->find($idForum);
 
         $form = $this->createForm(AddTopicType::class, $topicEntity);
         $form->handleRequest($request);
 
         if ($form->isSubmitted() && $form->isValid()) {
             $topicEntity->setForum($forumEntity);
             $topicEntity->setCreatedAt(new DateTime('now'));
             $topicEntity->setCreatedBy($user);
 
             $em->persist($topicEntity);
             $em->flush();
 
             return $this->redirectToRoute('app_topic',[
                 'idTopic' => $topicEntity->getId(),
                 'idForum' => $forumEntity->getId(),
             ]);
         }
 
         return $this->render('topic/addTopic.html.twig', [
             'form' => $form->createView(),
         ]);
    }

    // WARNING : fonction pour créer un message REFAIRE CA
    // Editer un topic
    #[Route('/forum/{idForum}/topic/edit/{idTopic}', name: 'app_topic_edit')]
    public function topicEdit(
        $idForum,
        ForumRepository $forumRepository,
        $idTopic,
        Request $request,
        EntityManagerInterface $em
        ): Response
    {
        $topic = $this->topicRepository->find($idTopic);
        $user = $this->getUser();
        $forumEntity = $this->forumRepository->find($idForum);

        $form = $this->createForm(AddTopicType::class, $topic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $topic->setForum($idForum);
            $topic->setCreatedAt(new DateTime('now'));
            $topic->setCreatedBy($user);

            $em->persist($topic);
            $em->flush();

            return $this->redirectToRoute('app_topic',[
                'idTopic' => $topic->getId(),
                'idForum' => $forumEntity->getId(),
            ]);
        }

        return $this->render('topic/addTopic.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Afficher un topic dans un forum
    #[Route('/forum/{idForum}/topic/{idTopic}', name: 'app_topic')]
    public function index(
        $idForum,
        $idTopic,
        ForumRepository $forumRepository,
        TopicRepository $topicRepository,
        Request $request,
        EntityManagerInterface $em
        ): Response

    {
        $forumEntity = $forumRepository->findOneBy(['id' => $idForum]);

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
                'idForum' => $forumEntity->getId(),
            ]);
        }
        return $this->render('topic/index.html.twig', [
            'topic' => $topicEntity,
            'form' => $form->createView(),
            'forumEntity' => $forumEntity,
        ]);
    }

    // Suppression d'un topic
    #[Route('/topic/delete/{idTopic}', name: 'app_topic_delete')]
    public function deleteTopic($idTopic, Request $request, EntityManagerInterface $em): Response
    {
        $topic = $this->topicRepository->find($idTopic);

        // foreach ($topic->getMessages() as $message) {
        //     dump($message->getContent());
        // }

        $em->remove($topic);
        $em->flush();

        return $this->redirectToRoute('app_forum');
    }
}
