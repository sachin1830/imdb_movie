<?php
namespace App\Services\ProductionCompany;

use App\Entity\ProdutionCompanys;
use App\Repository\ProductionCompanyRepository;
use Exception;

class ProductionCompnayService implements ProductionCompanyServiceInterface
{
    /**
     * @var ProductionCompanyRepository
     */
    private $companyRepo;
    function __construct(ProductionCompanyRepository $companyRepo)
    {
        $this->companyRepo = $companyRepo;
    }
    public function getCompany(int $id): ?ProdutionCompanys
    {
        $company = $this->companyRepo->find($id);
        if(!$company)
        {
            throw new Exception("Invalid id, company not found !!");   
        }
        return $company;
    }

    public function getAllCompanyes():array
    {
        $companys = $this->companyRepo->findAll();
        return $companys;
    }
    public function getAllCompanyesDESC():array
    {
        $companysdesc = $this->companyRepo->findByDESC();
        return $companysdesc;
    }
}

?>