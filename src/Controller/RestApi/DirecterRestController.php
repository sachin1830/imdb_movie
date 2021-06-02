<?php
namespace App\Controller\RestApi;

use App\Entity\Directers;

use App\Repository\DirecterRepository;
use App\Services\Directer\DirecterService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class DirecterRestController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var DirecterService
     */
    private $directerService;
    function __construct(EntityManagerInterface $entityManager, 
                        DirecterService $directerService)
    {
        $this->entityManager = $entityManager;
        $this->directerService = $directerService;
    }
    /**
     * @Route("/directer/",name="api_add_directer",methods={"POST"})
     */
    public function addDirecter(Request $request):JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $directer = new Directers();
        
        try {
            $directer = $this->setData($directer,$data);
        } catch (Exception $e) {
            echo "Error : ".$e->getMessage();

            return new JsonResponse(['status' => 'null value!'], 
                                Response::HTTP_BAD_REQUEST);
        }
        
        $this->entityManager->persist($directer);
        $this->entityManager->flush();

       return new JsonResponse(['status' => 'directer created!'], 
                                Response::HTTP_CREATED);
    }

    /**
     * @Route("/directer/{id}",name="api_get_directer",methods={"GET"})
     */
    public function getDirecter($id):JsonResponse
    {
        try {
            $directer = $this->directerService->getDirecter($id);
            
        } catch (Exception $e) {
            echo "Error : ".$e->getMessage();

            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }  
      

       $data= [ 
           'id' => $directer->getId(),
           'name' => $directer->getName(),
           'image' => $directer->getImage(),
           'gender' => $directer->getGender(),
           'desc' => $directer->getDescription(),
           'height' => $directer->getHeight(),
           'sign' => $directer->getSign()
       ];
       return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/directers/",name="api_get_directers",methods={"GET"})
     */
    public function getDirecters()
    {
       $direcers = $this->directerService->getAllDirecters();

       foreach($direcers as $directer)
       {
        $data[]= [ 
            'id' => $directer->getId(),
            'name' => $directer->getName(),
            'image' => $directer->getImage(),
            'gender' => $directer->getGender(),
            'desc' => $directer->getDescription(),
            'height' => $directer->getHeight(),
            'sign' => $directer->getSign()
        ];
       }
       return new JsonResponse($data, Response::HTTP_OK);
    }

     /**
     * @Route("/directer/{id}",name="api_update_directer",methods={"PUT"})
     */
    public function updateCast(int $id , Request $request):JsonResponse
    {
        try {
                $directer = $this->directerService->getDirecter($id);
            
        } catch (Exception $e) {
            echo "Error : ".$e->getMessage();

            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }  
        
        $data = json_decode($request-> getContent(),true);
        try {
            $directer=$this->setData($directer,$data);
        } catch (Exception $e) {

            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }
       
        $this->entityManager->persist($directer);
        $this->entityManager->flush();
        return new JsonResponse($directer->toArray(), Response::HTTP_OK);
    }

     /**
     * @Route("/directer/{id}",name="api_delete_directer",methods={"DELETE"})
     */
    public function deleteCast(int $id):JsonResponse
    {
        try {
                $directer = $this->directerService->getDirecter($id);
            
        } catch (Exception $e) {
            echo "Error : ".$e->getMessage();

            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }  
        
        $this->entityManager->remove($directer);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'directer deleted'], 
                        Response::HTTP_NO_CONTENT);
    }

    //this function used for setting the value of cast
    public function setData(Directers $directer, $data):Directers
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
      
        $directer->setName($name);
        $directer->setGender($gender);
        $directer->setImage($image);
        $directer->setDescription($desc);
        $directer->setHeight($height);
        $directer->setSign($sign);

        return $directer;
    }
    
}

?>