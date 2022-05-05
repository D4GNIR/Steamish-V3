<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Country;
use App\Entity\Game;
use App\Entity\Genre;
use App\Entity\Library;
use App\Entity\Publisher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function add(Game $game, bool $flush = true): void {
        $this->_em->persist($game);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Game $game, bool $flush = true): void {
        $this->_em->remove($game);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param string $orderBy une chaîne de caractère sur laquelle trier nos jeux
     * @param string $descAsc l'ordre du trie (par défaut DESC)
     * @param int|null $limit le nombre de jeux à récupérer (par défaut 9)
     * @return array
     */
    public function getMostGameByOrderBy(string $orderBy, string $descAsc = 'DESC', ?int $limit = 9): array
    {
        return $this->createQueryBuilder('g')
            ->join(Library::class, 'lib', Join::WITH, 'lib.game = g')
            ->groupBy('g.name')
            ->orderBy($orderBy, $descAsc)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    // select game, publi, country, genre, comments
    // join all
    // where slug
    // orderby commentaires triex desc
    // Pour les comments il faudra slice(0, 6) dans le html plus tard
    public function getALotOfThings(string $slug): ?Game
    {
        return $this->createQueryBuilder('g')
            ->select('g', 'com', 'cou', 'gen', 'pub')
            ->leftJoin('g.comments', 'com')
            ->join('g.countries', 'cou')
            ->join('g.genres', 'gen')
            ->leftJoin('g.publisher', 'pub')
            ->where('g.slug = :slug')
            ->orderBy('com.createdAt', 'DESC')
            ->setParameter(':slug', $slug)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
