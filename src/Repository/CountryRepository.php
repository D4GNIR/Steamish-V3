<?php

namespace App\Repository;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    public function add(Country $country, bool $flush = true): void {
        $this->_em->persist($country);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Country $country, bool $flush = true): void {
        $this->_em->remove($country);
        if ($flush) {
            $this->_em->flush();
        }
    }
}
