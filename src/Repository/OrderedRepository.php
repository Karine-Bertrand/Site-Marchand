<?php

namespace App\Repository;

use App\Entity\Ordered;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ordered|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ordered|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ordered[]    findAll()
 * @method Ordered[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ordered::class);
    }

    // /**
    //  * @return Ordered[] Returns an array of Ordered objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findUserOrdereds($value)
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.user', 'u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    public function findCompanyNonValidatedOrdereds($value)
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.company', 'c')
            ->innerJoin('c.user', 'u')
            ->where('u.id = :val AND o.validated = false')
            /* ->andWhere('o.validated = 0') */
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    public function findCompanyValidatedOrdereds($value)
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.company', 'c')
            ->innerJoin('c.user', 'u')
            ->where('u.id = :val AND o.validated = true')
            /* ->andWhere('o.validated = 0') */
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Ordered
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
