<?php

namespace App\Repository;

use App\Entity\ProdutionCompanys;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductionCompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProdutionCompanys::class);
    }

    public function findByDESC()
    {
       $queryBuilder = $this->createQueryBuilder('p');

       $queryBuilder->select('p.id,p.name,p.image')
                    ->orderBy('p.id','DESC');

       return $queryBuilder->getQuery()->getResult();

    }
}




?>