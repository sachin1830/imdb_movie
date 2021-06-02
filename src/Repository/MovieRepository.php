<?php

namespace App\Repository;

use App\Entity\Movies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movies::class);
    }

    public function findByDESC()
    {
       $queryBuilder = $this->createQueryBuilder('m');
       
       $queryBuilder->select('m.id,m.title,m.poster,m.releaseyear')
                    ->orderBy('m.id','DESC');

       return $queryBuilder->getQuery()->getResult();

    }
}


?>