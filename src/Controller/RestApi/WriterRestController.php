<?php
namespace App\Controller\RestApi;

use App\Entity\Writers;
use App\Services\Writer\WriterService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
class WriterRestController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var WriterService
     */
    private $writerService;
    function __construct(EntityManagerInterface $entityManager,
        WriterService $writerService)
    {
        $this->entityManager = $entityManager;
        $this->writerService = $writerService;
    }
    /**
     * @Route("/writer/",name="api_add_writer",methods={"POST"})
     */
    public function addWriter(Request $request):JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $writer = new Writers();

        try {
            $writer=$this->setData($writer,$data);

        } catch (Exception $e) {
            echo "Error : ".$e->getMessage();

            return new JsonResponse(['status' => 'null value!'], 
                                Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($writer);
        $this->entityManager->flush();

       return new JsonResponse(['status' => 'writer created!'], 
                                Response::HTTP_CREATED);
    }

    /**
     * @Route("/writer/{id}",name="api_get_writer",methods={"GET"})
     */
    public function getWriter(int $id):JsonResponse
    {
        try {
            $writer = $this->writerService->getWriter($id);

        } catch (\Exception $e) {
            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }

       $data= [ 
           'id' => $writer->getId(),
           'name' => $writer->getName(),
           'image' => $writer->getImage(),
           'gender' => $writer->getGender(),
           'desc' => $writer->getDescription(),
           'height' => $writer->getHeight(),
           'sign' => $writer->getSign()
       ];
       return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/writers/",name="api_get_writers",methods={"GET"})
     */
    public function getWriters()
    {
       $writers = $this->writerService->getAllWriters();

       foreach($writers as $writer)
       {
        $data[]= [ 
            'id' => $writer->getId(),
            'name' => $writer->getName(),
            'image' => $writer->getImage(),
            'gender' => $writer->getGender(),
            'desc' => $writer->getDescription(),
            'height' => $writer->getHeight(),
            'sign' => $writer->getSign()
        ];
       }
       return new JsonResponse($data, Response::HTTP_OK);
    }

     /**
     * @Route("/writer/{id}",name="api_update_writer",methods={"PUT"})
     */
    public function updateWriter(int $id , Request $request):JsonResponse
    {
        try {
            $writer = $this->writerService->getWriter($id);

        } catch (\Exception $e) {
            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request-> getContent(),true);
        try {
            
            $writer=$this->setData($writer,$data);

        } catch (Exception $e) {
            echo "Error : ".$e->getMessage();

            return new JsonResponse(['status' => 'null value!'], 
                                Response::HTTP_BAD_REQUEST);
        }
        $this->entityManager->persist($writer);
        $this->entityManager->flush();
        return new JsonResponse($writer->toArray(), Response::HTTP_OK);
    }


     /**
     * @Route("/writer/{id}",name="api_delete_writer",methods={"DELETE"})
     */
    public function deleteWriter(int $id):JsonResponse
    {
       try {
            $writer = $this->writerService->getWriter($id);

        } catch (\Exception $e) {
            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }
         
        $this->entityManager->remove($writer);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'directer deleted'], 
                        Response::HTTP_NO_CONTENT);
    }

    //this function used for setting the value of cast
    public function setData(Writers $writer, $data):Writers
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

        $writer->setName($name);
        $writer->setGender($gender);
        $writer->setImage($image);
        $writer->setDescription($desc);
        $writer->setHeight($height);
        $writer->setSign($sign);

        return $writer;
    }
    
}

?>