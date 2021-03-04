<?php

namespace App\Repository;

use App\Entity\Generate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Generate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Generate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Generate[]    findAll()
 * @method Generate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenerateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Generate::class);
    }

    // /**
    //  * @return Generate[] Returns an array of Generate objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Generate
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
