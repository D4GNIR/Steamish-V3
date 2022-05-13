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

    public function getRelatedGames(Game $game) {
        return $this->createQueryBuilder('g')
            ->select('g')
            ->join('g.genres', 'genres')
            ->where('genres IN (:genres)')
            ->setParameter('genres', $game->getGenres())
            ->andWhere('g != :currentGame')
            ->setParameter('currentGame', $game)
            ->orderBy('g.publishedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // Fonction pour récupérer tous les jeux d'un genre en particulier
    public function getGamesOfOneGenre(string $slug) {
        return $this->createQueryBuilder('g')
            ->select('g')
            ->join('g.genres', 'genres')
            ->where('genres.slug = :slug')
            ->setParameter(':slug', $slug)
            ->getQuery()
            ->getResult()
        ;
    }

    // Fonction pour récupérer tous les jeux d'une langue en particulier
    public function getGamesOfOneLanguage() {
        
    }

    public function searchGame(string $value) {
        return $this->createQueryBuilder('g')
            ->select('g')
            ->where('g.name LIKE :value')
            ->setParameter('value', '%'.$value.'%')
            ->getQuery()
            ->getResult()
        ;
    }

    
    public function getQbAll()
    {
        return $this->createQueryBuilder('g');
    }

    public function updateQbByData($qb, $datas)
    {
            if(isset($datas['name'])){
                $qb        
                ->andWhere('g.name LIKE :name')
                ->setParameter('name', '%'.$datas['name'].'%');
            } 
            if(isset($datas['price_max'])){
                $qb        
                ->andWhere('g.price < :price_max')
                ->setParameter('price_max', $datas['price_max']);
            } 
            if(isset($datas['price_min'])){
                $qb        
                ->andWhere('g.price > :price_min')
                ->setParameter('price_min', $datas['price_min']);
            }
            if(count($datas['genres']) > 0){
                $qb
                ->join('g.genres', 'genre')
                ->andWhere('genre IN (:genre_array)')
                ->setParameter('genre_array', $datas['genres']);
            }
            if(isset($datas['published_at'])){
                $qb
                ->andWhere('g.publishedAt > :published_at')
                ->setParameter('published_at', $datas['published_at']);
            }
           


            return $qb;
    }
}
