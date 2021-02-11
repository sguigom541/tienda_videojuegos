<?php

namespace App\Repository;

use App\Entity\Plataforma;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Plataforma|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plataforma|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plataforma[]    findAll()
 * @method Plataforma[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlataformaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plataforma::class);
    }

    // /**
    //  * @return Plataforma[] Returns an array of Plataforma objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Plataforma
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
