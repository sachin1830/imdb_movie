<?php
namespace App\Services\Country;

use App\Entity\Countrys;

interface CountryServiceInterface
{
    public function getAllCountry():array;
    public function getCountry(int $id): ?Countrys;
}


?>