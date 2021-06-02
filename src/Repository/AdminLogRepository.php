<?php

namespace App\Repository;

use App\Entity\AdminLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdminLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminLog[]    findAll()
 * @method AdminLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdminLog::class);
    }

    // /**
    //  * @return AdminLog[] Returns an array of AdminLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdminLog
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
