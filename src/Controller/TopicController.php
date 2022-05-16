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

    // Création d'un topic avec un message
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

    // Edition d'un topic
    #[Route('/forum/{idForum}/topic/edit/{idTopic}', name: 'app_topic_edit')]
    public function topicEdit(
        $idForum,
        $idTopic,
        Request $request,
        EntityManagerInterface $em
        ): Response
    {
        $topicEntity = $this->topicRepository->find($idTopic); // Le topic
        $forumEntity = $this->forumRepository->find($idForum); // Le forum où est situé le topic

        $form = $this->createForm(AddTopicType::class, $topicEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

    // Suppression d'un topic
    #[Route('/forum/{idForum}/topic/delete/{idTopic}', name: 'app_topic_delete')]
    public function deleteTopic(
        $idForum,
        $idTopic,
        EntityManagerInterface $em
        ): Response
    {
        $topicEntity = $this->topicRepository->find($idTopic);
        $forumEntity = $this->forumRepository->find($idForum);

        $em->remove($topicEntity);
        $em->flush();

        return $this->redirectToRoute('app_forum_show',[
            'idForum' => $forumEntity->getId(),
        ]);
    }

    // Affichage d'un topic dans un forum
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

    // Edition d'un topic
    #[Route('/forum/{idForum}/topic/{idTopic}/edit/{idMessage}', name: 'app_message_edit')]
    public function messageEdit(
        $idForum,
        $idTopic,
        $idMessage,
        Request $request,
        EntityManagerInterface $em
        ): Response
    {
        $messageEntity = $this->messageRepository->find($idMessage);
        $topicEntity = $this->topicRepository->find($idTopic);
        $forumEntity = $this->forumRepository->find($idForum);

        $form = $this->createForm(AddMessageType::class, $messageEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($messageEntity);
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
    
    // Suppression d'un message
    #[Route('/forum/{idForum}/topic/{idTopic}/delete/{idMessage}', name: 'app_message_delete')]
    public function deleteMessage(
        $idForum,
        $idTopic,
        $idMessage,
        EntityManagerInterface $em
        ): Response
    {
        $messageEntity = $this->messageRepository->find($idMessage);
        $topicEntity = $this->topicRepository->find($idTopic);
        $forumEntity = $this->forumRepository->find($idForum);

        $em->remove($messageEntity);
        $em->flush();

        return $this->redirectToRoute('app_topic',[
            'idTopic' => $topicEntity->getId(),
            'idForum' => $forumEntity->getId(),
        ]);
    }
}
