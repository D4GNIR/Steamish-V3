<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Comment;
use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function add(Comment $comment, bool $flush = true): void {
        $this->_em->persist($comment);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Comment $comment, bool $flush = true): void {
        $this->_em->remove($comment);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getCommentByAccountAndByGame(Account $account, Game $game)
    {
        return $this->createQueryBuilder('c')
            ->join('c.account', 'account')
            ->join('c.game', 'game')
            ->where('account = :account_entity')
            ->andWhere('game = :game_entity')
            ->setParameter('account_entity', $account)
            ->setParameter('game_entity', $game)
            ->setMaxResults(1) // Je le force à me rendre un seul résultat
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
