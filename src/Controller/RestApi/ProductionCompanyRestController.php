<?php
namespace App\Controller\RestApi;

use App\Entity\ProdutionCompanys;
use App\Services\ProductionCompany\ProductionCompnayService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductionCompanyRestController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ProductionCompnayService
     */
    private $productionCompnayService;
    function __construct(EntityManagerInterface $entityManager, 
                        ProductionCompnayService $productionCompnayService)
    {
        $this->entityManager = $entityManager;
        $this->productionCompnayService = $productionCompnayService;
    }
    /**
     * @Route("/company/",name="api_add_company",methods={"POST"})
     */
    public function addCompany(Request $request):JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $company = new ProdutionCompanys();
        try {
            $company =  $this->setData($company,$data);

        } catch (\Exception $e) {
            echo "Error : ".$e->getMessage();

            return new JsonResponse(['status' => 'null value!'], 
                                Response::HTTP_BAD_REQUEST);
        }


        $this->entityManager->persist($company);
        $this->entityManager->flush();

       return new JsonResponse(['status' => 'production company created!'], 
                                Response::HTTP_CREATED);
    }

    //This method fetch the productioncompany by id
    /**
     * @Route("/company/{id}",name="api_get_company",methods={"GET"})
     */
    public function getCompany(int $id):JsonResponse
    {
        try {
            $company = $this->productionCompnayService->getCompany($id);

        } catch (\Exception $e) {
            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }

       $data= [ 
           'id' => $company->getId(),
           'name' => $company->getName(),
           'image' => $company->getImage(),
           'about' => $company->getAbout()
       ];
       return new JsonResponse($data, Response::HTTP_OK);
    }

    //This method fetch all the production company
    /**
     * @Route("/companys/",name="api_get_companys",methods={"GET"})
     */
    public function getcompanys()
    {
       $companys = $this->productionCompnayService->getAllCompanyes();

       foreach($companys as $company)
       {
        $data[]= [ 
            'id' => $company->getId(),
           'name' => $company->getName(),
           'image' => $company->getImage(),
           'about' => $company->getAbout()
        ];
       }
       return new JsonResponse($data, Response::HTTP_OK);
    }

     /**
     * @Route("/company/{id}",name="api_update_company",methods={"PUT"})
     */
    public function updateCompany(int $id , Request $request):JsonResponse
    {
        try {
            $company = $this->productionCompnayService->getCompany($id);
            
        } catch (\Exception $e) {
            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }
        $data = json_decode($request-> getContent(),true);

        try {
            $company =  $this->setData($company,$data);
        } catch (\Exception $e) {
            echo "Error : ".$e->getMessage();

            return new JsonResponse(['status' => 'null value!'], 
                                Response::HTTP_BAD_REQUEST);
        }
        $this->entityManager->persist($company);
        $this->entityManager->flush();
        return new JsonResponse($company->toArray(), Response::HTTP_OK);
        
    }
     /**
     * @Route("/company/{id}",name="delete_company",methods={"DELETE"})
     */
    public function deleteCompany(int $id):JsonResponse
    {
        try {
            $company = $this->productionCompnayService->getCompany($id);
            
        } catch (\Exception $e) {
            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }
        
        $this->entityManager->remove($company);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'company deleted'], 
                        Response::HTTP_NO_CONTENT);
    }

    //this function used for setting the value of cast
    public function setData(ProdutionCompanys $company, $data):ProdutionCompanys
    {
        $name=$data['name'];
        $about=$data['about'];
        $image=$data['image'];
        if ($name == "" || $about == "" || $image == "") {
            
            throw new Exception("Any value can not be null");
        }
        $company->setName($name);
        $company->setAbout($about);
        $company->setImage($image);
      
        return $company;
    }
    
}

?>