<?php

namespace App\Repository;

use App\Entity\Casts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CastsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Casts::class);
    }

    public function findByDESC()
    {
       $queryBuilder = $this->createQueryBuilder('c');


       $queryBuilder->select('c.id,c.name,c.image,c.gender')
                    ->orderBy('c.id','DESC');

       return $queryBuilder->getQuery()->getResult();

    }

    
}


?>