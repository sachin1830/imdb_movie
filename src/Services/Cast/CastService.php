<?php
namespace App\Services\Cast;

use App\Entity\Casts;
use App\Repository\CastsRepository;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CastService implements CastServiceInterface
{
    /**
     * @var CastsRepository
     */
    private $castRepo;

    function __construct(CastsRepository $castRepo) 
    {
       $this-> castRepo = $castRepo;
    }

    public function getCast(int $id): ?Casts
    {
        $cast = $this->castRepo->find($id);
        if (!$cast) {
            throw new Exception("Invalid cast id, cast not found");
            
        }
        return $cast;
    }

    public function getAllCasts():array
    {
        $casts = $this->castRepo->findAll();
        return $casts;
    }

    public function getAllCastDESC():array
    {
       $castsdesc = $this->castRepo->findByDESC();
       return $castsdesc;
    }
}


?>