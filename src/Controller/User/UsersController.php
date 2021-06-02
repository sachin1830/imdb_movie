<?php

namespace App\Controller\User;

use App\Entity\MovieReview;
use App\Entity\Movies;
use App\Entity\User;
use App\EventListener\UserEvent\ReviewEvent;
use App\Services\Movie\MovieService;
use App\Services\MovieReview\MovieReviewService;
use App\Services\User\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UsersController extends AbstractController
{
    /**
     * @var UserService
     */
    private $userService;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MovieService
     */
    private $movieService;
    /**
     * @var MovieReviewService
     */
    private $movieReviewService;

    /**
     * @var EventDispatcherInterface
     */

    private $eventDispatcher;

    function __construct(UserService $userService, 
    EntityManagerInterface $entityManager, MovieService $movieService , 
    MovieReviewService $movieReviewService, 
    EventDispatcherInterface $eventDispatcher)
    {
        $this->userService = $userService;
        $this->entityManager = $entityManager;
        $this->movieService = $movieService;
        $this->movieReviewService = $movieReviewService;
        $this->eventDispatcher = $eventDispatcher;
    }
    /**
     * @Route("/registration",name="user_registration")
     */
    public function registerUser():Response
    {
        if(isset($_POST['submit']))
        {
            $user = new User();
            $name = $_POST['name'];
            $email = $_POST['email'];
            $pass = $_POST['password'];
            $cpass = $_POST['cpassword'];

            $hashPass = password_hash($pass,PASSWORD_BCRYPT);

            $fetchUser = $this->userService->getUserByEmail($email);

            if($fetchUser)
            {
                $this->addFlash('error',"This email already register");
                return $this->render("form/registration.html.twig",[
                    'name' =>$name,
                    'email' =>$email,
                    'pass'=>$pass
                ]);
            }
            else{
                if($pass === $cpass)
                {
                    $user->setName($name);
                    $user->setEmail($email);
                    $user->setPassword($hashPass);
                    $user->setRole(['ROLE_USER']);
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();
                    $this->addFlash("register_success","You register succesflly");
                    return $this->redirectToRoute("user_login");
                }
                else
                {
                    $this->addFlash('error',"Password and conform password not matched");
                    return $this->render("form/registration.html.twig",[
                        'name' =>$name,
                        'email' =>$email,
                        'pass'=>$pass
                    ]);
                }    
            }
        }
        return $this->render('form/registration.html.twig',[
            'name' =>"",
            'email' =>"",
            'pass' => ""
        ]);
    
    }

     /**
      * @IsGranted("ROLE_USER")
     * @Route("/movie/review/{id}",name="user_review")
     */
    public function userReview(int $id)
    {
            $fetchMovie = $this->movieService->getMovie($id);
            $fetchUser=$this->getUser();
            
            if(isset($_POST['submit']))
            {
                $rating = $_POST['rating'];
                $review = $_POST['review'];

                if($rating == "")
                {
                    $rating = 1;
                }
                else
                {
                    $movieReview = new MovieReview();
                    $movieReview->setRating($rating);
                    $movieReview->setReview($review);
                    $movieReview->setMovies($fetchMovie);
                    $movieReview->setUser($fetchUser);

                    $this->entityManager->persist($movieReview);
                    $this->entityManager->flush();
                    $this->addFlash("reviewflash","Review added successfully");
                    
                    $path = "App\Controller\User\UsersController::userReview";
                    $event = new ReviewEvent($fetchUser,$fetchMovie,$rating,$path);
                    $this->eventDispatcher->dispatch($event,ReviewEvent::class);

                    return $this->redirectToRoute("movie_details",[
                        'id' =>$fetchMovie->getId()
                    ]);
                }
            }

            return $this->render("admin_movie/movie_review.html.twig",[
                'fetchMovie' =>$fetchMovie
            ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/usercomment/delete/{id}",name="delete_comment")
     */
    public function deleteComment(int $id)
    {
        $fetchReview = $this->movieReviewService->getReview($id);
        /**
         * @var Movies
         */
        $movie = $fetchReview->getMovies();
        $movieId = $movie->getId();
    
        if($fetchReview)
        {
            $this->entityManager->remove($fetchReview);
        }
        else
        {
            echo"No data found";
        }
        
        $this->entityManager->flush();
        $this->addFlash('reviewflash',"comment remove successfully");

        return $this->redirectToRoute("movie_details",[
            'id' =>$movieId
        ]);
       
    }
}
?>