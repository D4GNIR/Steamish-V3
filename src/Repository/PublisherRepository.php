<?php

namespace App\Repository;

use App\Entity\Publisher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Publisher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publisher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publisher[]    findAll()
 * @method Publisher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublisherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publisher::class);
    }

    public function add(Publisher $publisher, bool $flush = true): void {
        $this->_em->persist($publisher);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Publisher $publisher, bool $flush = true): void {
        $this->_em->remove($publisher);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Publisher[]
     */
    public function getPublishersAll(): array {
        return $this->createQueryBuilder('p')
            ->select('p', 'country', 'games')
            ->join('p.country', 'country')
            ->join('p.games', 'games')
            ->orderBy('p.name')
//            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
        ;
    }


}
