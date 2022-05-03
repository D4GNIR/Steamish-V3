<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Library;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Library|null find($id, $lockMode = null, $lockVersion = null)
 * @method Library|null findOneBy(array $criteria, array $orderBy = null)
 * @method Library[]    findAll()
 * @method Library[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LibraryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Library::class);
    }

    public function add(Library $library, bool $flush = true): void {
        $this->_em->persist($library);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Library $library, bool $flush = true): void {
        $this->_em->remove($library);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
