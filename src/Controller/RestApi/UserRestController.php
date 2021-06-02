<?php
namespace App\Controller\RestApi;

use App\Entity\MovieReview;
use App\Entity\User;
use App\Services\Movie\MovieService;
use App\Services\MovieReview\MovieReviewService;
use App\Services\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserRestController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var MovieService
     */
    private $movieService;
    /**
     * @var MovieReviewService
     */
    private $movieReviewService;
    function __construct(EntityManagerInterface $entityManager, 
    UserService $userService, MovieService $movieService , 
        MovieReviewService $movieReviewService)
    {
        $this->entityManager = $entityManager;
        $this->userService = $userService;
        $this->movieService = $movieService;
        $this->movieReviewService = $movieReviewService;
    }
    /**
     * @Route("/user/",name="api_add_user",methods={"POST"})
     */
    public function addUser(Request $request):JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $name = $data['name'];
        $email = $data['email'];
        $password = $data['password'];
        try {

            if ($name == "" || $email == "" || $password == "") {
                
                throw new Exception("Any value can not be null");
            }
        } catch (Exception $e) {
            echo"Error : ".$e->getMessage();

            return new JsonResponse(['status' => 'null value!'], 
                                Response::HTTP_BAD_REQUEST);
        }
        
        try {
            $checkEmail = $this->userService->getUserByEmail($email);
            if($checkEmail)
            {
                throw new Exception("Email already register");
            }
        } catch (\Exception $e) {
            echo "Error : ".$e->getMessage();

            return new JsonResponse(['status' => 'already register!'], 
            Response::HTTP_BAD_REQUEST);
        }

        $hashPass = password_hash($password,PASSWORD_BCRYPT);
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword($hashPass);
        $user->setRole(["ROLE_USER"]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

       return new JsonResponse(['status' => 'user created created!'], 
                                Response::HTTP_CREATED);
    }

    //Fetch the registeruser by id
    /**
     * @Route("/user/{id}",name="api_get_user",methods={"GET"})
     */
    public function getRegisterUser(int $id):JsonResponse
    {
        /**
         * @var User
         */
        try {
            $user = $this->userService->getUsers($id);
            
        } catch (\Exception $e) {

            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }

       $data= [ 
           'id' => $user->getId(),
           'name' => $user->getName(),
           'email' => $user->getEmail(),
           'password' => $user->getPassword(),
           'role' => $user->getRoles()
       ];
       return new JsonResponse($data, Response::HTTP_OK);
    }

    //Get all the register user
    /**
     * @Route("/users/",name="api_get_users",methods={"GET"})
     */
    public function getRegisterUsers()
    {
        /**
         * @var User
         */
       $users = $this->userService->getAllUser();

       foreach($users as $user)
       {
        $data[]= [ 
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'role' => $user->getRoles()
        ];
       }
       return new JsonResponse($data, Response::HTTP_OK);
    }

     /**
     * @Route("/user/{id}",name="api_update_user",methods={"PUT"})
     */
    public function updateUser(int $id , Request $request):JsonResponse
    {
        try {
            $user = $this->userService->getUsers($id);

        } catch (\Exception $e) {

            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request-> getContent(),true);
        try {
            $name = $data['name'];
            $email = $data['email'];
            if ($name == "" || $email == "") {

                throw new Exception("Any value can not be null");
            }
        } catch (\Exception $e) {
            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'null value!'], 
                                Response::HTTP_BAD_REQUEST);
        }

        $user->setName($name);
        $user->setEmail($email);
        $user->setRole(["ROLE_USER"]);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return new JsonResponse($user->toArray(), Response::HTTP_OK);
    }


     /**
     * @Route("/user/{id}",name="api_delete_user",methods={"DELETE"})
     */
    public function deleteUser(int $id):JsonResponse
    {
        try {
            $user = $this->userService->getUsers($id);
            
        } catch (\Exception $e) {

            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }
        
        if($user)
        {
            $this->entityManager->remove($user);
        }
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'directer deleted'], 
                        Response::HTTP_NO_CONTENT);
    }

     /**
     * @Route("/review/",name="api_add_review",methods={"POST"})
     */
    public function userReview(Request $request):JsonResponse
    {
       $data = json_decode($request-> getContent(),true);
       $userId = $data['userid'];
       $movieId = $data['movieid'];
       $rating = $data['rating'];
       $review = $data['review'];

       try {
            if ($rating == "" || $review == "") {
                throw new Exception("Any value can not be null");
            }
       } catch (\Exception $e) {
        echo "Error : ".$e->getMessage();

        return new JsonResponse(['status' => 'Not null!'], 
        Response::HTTP_BAD_REQUEST);
       }

       try {
            $user = $this->userService->getUsers($userId);
            
        } catch (\Exception $e) {

            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }
        try {
            $movie = $this->movieService->getMovie($movieId);
            if(!$movie)
            {
                throw new Exception("Invalid id, movie not found !!");   
            }
        } catch (\Exception $e) {
            echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }
            /**
             * @var MovieReview
             */
            $movieReview = new MovieReview();
            $movieReview->setRating($rating);
            $movieReview->setReview($review);
            $movieReview->setMovies($movie);
            $movieReview->setUser($user);

            $this->entityManager->persist($movieReview);
            $this->entityManager->flush();
            
       return new JsonResponse(['status' => 'review added sucessfully!'], 
       Response::HTTP_CREATED);
    }

     /**
     * @Route("/review/{id}",name="api_delete_review",methods={"DELETE"})
     */
    public function deleteUserreview(int $id):JsonResponse
    {
        try {
            $review = $this->movieReviewService->getReview($id);

            if(!$review)
            {
                throw new Exception("Invalid id, review not found !!");   
            }
        } catch (\Exception $e) {
            echo "Error : ".$e->getMessage();

            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }
       
       if($review)
       {
           $this->entityManager->remove($review);
       }
       $this->entityManager->flush();

        return new JsonResponse(['status' => 'review deleted'], 
                        Response::HTTP_NO_CONTENT);

    }
}

?>