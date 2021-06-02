<?php
namespace App\Services\ProductionCompany;

use App\Entity\ProdutionCompanys;

interface ProductionCompanyServiceInterface
{
    public function getAllCompanyes():array;
    public function getCompany(int $id): ?ProdutionCompanys;
    public function getAllCompanyesDESC():array;
}
?>