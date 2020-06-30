<?php

namespace App\Repository;

use App\Entity\Drive;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Drive|null find($id, $lockMode = null, $lockVersion = null)
 * @method Drive|null findOneBy(array $criteria, array $orderBy = null)
 * @method Drive[]    findAll()
 * @method Drive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DriveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Drive::class);
    }

    // /**
    //  * @return Drive[] Returns an array of Drive objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findAllWithAddress()
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.address', 'a')
            ->getQuery()
            ->getResult();
    }

    public function findCompanyDrives($value)
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.address', 'a')
            ->innerJoin('d.companies', 'c')
            ->innerJoin('c.user', 'u')
            ->andWhere('u.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Drive
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
