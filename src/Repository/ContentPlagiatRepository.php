<?php

namespace App\Repository;

use App\Entity\ContentPlagiat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContentPlagiat|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentPlagiat|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentPlagiat[]    findAll()
 * @method ContentPlagiat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentPlagiatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContentPlagiat::class);
    }

    // /**
    //  * @return ContentPlagiat[] Returns an array of ContentPlagiat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContentPlagiat
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
