<?php

namespace App\Repository;

use App\Entity\Directers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DirecterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Directers::class);
    }

    public function findByDESC()
    {
       $queryBuilder = $this->createQueryBuilder('d');

       $queryBuilder->select('d.id,d.name,d.image,d.gender')
                    ->orderBy('d.id','DESC');

       return $queryBuilder->getQuery()->getResult();

    }
}


?>