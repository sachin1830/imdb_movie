<?php
namespace App\Services\Directer;

use App\Entity\Directers;

interface DirecterServiceInterface
{
    public function getAllDirecters():array;
    public function getDirecter(int $id): ?Directers;
    public function getAllDirectersDESC():array;
}

?>