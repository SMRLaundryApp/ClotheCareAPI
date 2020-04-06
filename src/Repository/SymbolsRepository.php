<?php

namespace App\Repository;

use App\Entity\Symbols;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Symbols|null find($id, $lockMode = null, $lockVersion = null)
 * @method Symbols|null findOneBy(array $criteria, array $orderBy = null)
 * @method Symbols[]    findAll()
 * @method Symbols[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SymbolsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Symbols::class);
    }

    // /**
    //  * @return Symbols[] Returns an array of Symbols objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Symbols
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
