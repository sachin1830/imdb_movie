<?php
namespace App\Services\Cast;

use App\Entity\Casts;

interface CastServiceInterface
{
    public function getAllCasts():array;
    public function getCast(int $id): ?Casts;
    public function getAllCastDESC():array;
}
?>