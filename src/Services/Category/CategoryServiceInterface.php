<?php
namespace App\Services\Category;

use App\Entity\Categores;

interface CategoryServiceInterface
{
    public function getAllCategory():array;
    public function getCategory(int $id): ?Categores;

}

?>