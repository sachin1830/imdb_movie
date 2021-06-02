<?php

namespace App\Repository;

use App\Entity\Writers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class WritersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Writers::class);
    }

    public function findByDESC()
    {
       $queryBuilder = $this->createQueryBuilder('w');
       
       $queryBuilder->select('w.id,w.name,w.image,w.gender')
                    ->orderBy('w.id','DESC');
       return $queryBuilder->getQuery()->getResult();

    }

}


?>