<?php
namespace App\Controller\RestApi;

use App\Entity\Casts;
use App\Services\Cast\CastService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class CastRestController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var CastService
     */
    private $castService;

    function __construct(EntityManagerInterface $entityManager,
                        CastService  $castService)
    {
        $this->entityManager = $entityManager;
        $this->castService = $castService;
    }

    /**
     * @Route("/cast/",name="api_add_cast",methods={"POST"})
     */
    public function addCast(Request $request):JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $cast = new Casts();
        try {
            $cast=$this->setData($cast,$data);

        } catch (Exception $e) {
            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'null value!'], 
                                Response::HTTP_BAD_REQUEST);
        }
        $this->entityManager->persist($cast);
        $this->entityManager->flush();

       return new JsonResponse(['status' => 'cast created!'], 
                                Response::HTTP_CREATED);
    }

    //This method fetching cast by id
    /**
     * @Route("/cast/{id}",name="api_get_cast",methods={"GET"})
     */
    public function getCast(int $id):JsonResponse
    {
        try {
        $cast = $this->castService->getCast($id);

        } catch (Exception $e) {
            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }  
       $data= [ 
           'id' => $cast->getId(),
           'name' => $cast->getName(),
           'image' => $cast->getImage(),
           'gender' => $cast->getGender(),
           'desc' => $cast->getDescription(),
           'height' => $cast->getHeight(),
           'sign' => $cast->getSign()
       ];
       return new JsonResponse($data, Response::HTTP_OK);
    }

    //This method fetching the casts
    /**
     * @Route("/casts/",name="api_get_casts",methods={"GET"})
     */
    public function getCasts()
    {
       $casts = $this->castService->getAllCasts();

       foreach($casts as $cast)
       {
        $data[]= [ 
            'id' => $cast->getId(),
            'name' => $cast->getName(),
            'image' => $cast->getImage(),
            'gender' => $cast->getGender(),
            'desc' => $cast->getDescription(),
            'height' => $cast->getHeight(),
            'sign' => $cast->getSign()
        ];
       }
       return new JsonResponse($data, Response::HTTP_OK);
    }

     /**
     * @Route("/cast/{id}",name="api_update_cast",methods={"PUT"})
     */
    public function updateCast(int $id, Request $request):JsonResponse
    {
        try {
            $cast = $this->castService->getCast($id);

        } catch (Exception $e) {
            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }
        
        $data = json_decode($request-> getContent(),true);

        try {
            $cast=$this->setData($cast,$data);

        } catch (Exception $e) {
            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'null value!'], 
                                Response::HTTP_BAD_REQUEST);
        }
        
        $this->entityManager->persist($cast);
        $this->entityManager->flush();
        return new JsonResponse($cast->toArray(), Response::HTTP_OK);
    }

     /**
     * @Route("/cast/{id}",name="api_delete_cast",methods={"DELETE"})
     */
    public function deleteCast(int $id):JsonResponse
    {
        try {
            $cast = $this->castService->getCast($id);

        } catch (Exception $e) {
             echo "Error : ".$e->getMessage();

            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($cast);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Customer deleted'], 
                        Response::HTTP_NO_CONTENT);
    }

    //this function used for setting the value of cast
    public function setData(Casts $cast, $data):Casts
    {
        $name=$data['name'];
        $gender=$data['gender'];
        $image=$data['image'];
        $desc=$data['description'];
        $height=$data['height'];
        $sign=$data['sign'];

        if ($name == "" || $gender == "" || $image == "" || $desc == ""
        || $height == "" || $sign == "") {
            
            throw new Exception("Any value can not be null");
        }
      
        $cast->setName($name);
        $cast->setGender($gender);
        $cast->setImage($image);
        $cast->setDescription($desc);
        $cast->setHeight($height);
        $cast->setSign($sign);

        return $cast;
    }
    
}

?>