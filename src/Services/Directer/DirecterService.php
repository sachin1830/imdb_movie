<?php
namespace App\Services\Directer;

use App\Entity\Directers;
use App\Repository\DirecterRepository;
use Exception;

class DirecterService implements DirecterServiceInterface
{
    /**
     * @var DirecterRepository
     */
    private $directerRepo;
    function __construct(DirecterRepository $directerRepo) 
    {
       $this-> directerRepo = $directerRepo;
    }

    public function getDirecter(int $id): ?Directers
    {
        
        $directer = $this->directerRepo->find($id);
        if(!$directer)
        {
            throw new Exception("Invalid id, directer not found !!");   
        }
        return $directer;
    }

    public function getAllDirecters(): array
    {
        $directers = $this->directerRepo->findAll();
        return $directers;
    }

    public function getAllDirectersDESC():array
    {
        $directersdesc = $this->directerRepo->findByDESC();
        return $directersdesc;
    }
}

?>