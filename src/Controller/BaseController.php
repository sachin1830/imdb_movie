<?php
namespace App\Controller;

use App\Entity\MovieMedia;
use App\Entity\Movies;
use App\EventListener\UserEvent\LoginEvent;
use App\EventListener\UserEvent\MovieEvent;
use App\EventListener\UserEvent\MovieMediaEvent;
use App\Services\Movie\MovieService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class BaseController extends AbstractController
{
    /**
     * @var MovieService
     */
    private $movieService;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    function __construct(MovieService $movieService, 
                        EventDispatcherInterface $eventDispatcher)
    {
        $this->movieService = $movieService;
        $this->eventDispatcher = $eventDispatcher;
    }
    /**
     * @Route("/",name="index_page")
     */
    public function indexPage():Response
    {
        return $this->render("main/index.html.twig");
    }

    /**
     * @Route("/userlogin",name="user_login")
     */
    public function userLogin(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        
        return $this->render("form/login.html.twig",[
            'last_username' => $lastUsername,
            'error' => $error
            ]);
    }

     /**
     * @Route("/adminlogin",name="admin_login")
     */
    public function adminLogin(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("admin/admin_login.html.twig",[
            'last_username' => $lastUsername,
            'error' => $error
            ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/movie_critic",name="load_movies")
     */
    public function loadMovie(SessionInterface $session):Response
    {
        $allMovies = $this->movieService->getAllMovies();

        if(!$session->has("userlogin"))
        {
            $session->set("userlogin","Logindone");
            $user = $this->getUser();
            $path = "App\Controller\BaseController::loadMovie";
            $event = new LoginEvent($user,$path);
            $this->eventDispatcher->dispatch($event,LoginEvent::class);
        }

        return $this->render("main/home_page.html.twig",[
            'movielist' =>$allMovies 
        ]);

    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/movie_details/{id}",name="movie_details")
     */
    public function loadMovieDetails(int $id):Response
    {
        $fetchMovie = $this->movieService->getMovie($id);
        $imageArray = $this->loadImages($fetchMovie);
        $trailerUrl = $this->loadTrailer($fetchMovie);
        
        $rating=$this->ratingCalculation($fetchMovie);

        $path = "App\Controller\BaseController::loadMovieDetails";
        $user = $this->getUser();
        $event = new MovieEvent($user,$fetchMovie,$path);
        $this->eventDispatcher->dispatch($event,MovieEvent::class);

        return $this->render("admin_movie/movie_details.html.twig",[
            'fetchMovie' => $fetchMovie,
            'imageArray' =>$imageArray,
            'videoUrl' =>$trailerUrl,
            'rating' => $rating
            ]);
        
            //dd($fetchMovie ->getReview()[1]->getUser()->getName());
        
    }

    /**
     * @Route("/trying",name="for trying")
     */
    public function forTry()
    {
        return $this->render("form/testing.html");
    }

    public function loadImages(Movies $fetchMovie)
    {
        $mediaArray = $fetchMovie->getMovieMedia();
        $length = count($mediaArray);
        $imageArray = array();

        for($i = 0; $i<$length; $i++)
        {
            $image = $mediaArray[$i]->getMediafile();
            $imageDiv = explode(".",$image);
            $imageExt = end($imageDiv);
            if($imageExt == "png" || $imageExt == "jpg" || $imageExt == "jpeg")
            {
                $imageArray[$i] = $image;
            }
        }
        return $imageArray;
    }

    public function loadVideo(Movies $fetchMovie)
    {
        $mediaArray = $fetchMovie->getMovieMedia();
        $length = count($mediaArray);
        $videoArray = array();

        for($i = 0; $i<$length; $i++)
        {
            $file = $mediaArray[$i]->getMediafile();
            $fileDiv = explode(".",$file);
            $videoExt = end($fileDiv);
            if($videoExt == "mkv" || $videoExt == "mp4")
            {
                $videoArray[$i] = $file;
            }
        }
        return $videoArray;
    }

    public function loadTrailer(Movies $fetchMovie)
    {
        $mediaArray = $fetchMovie->getMovieMedia();
        $length = count($mediaArray);
        $videoUrl = "";

        for($i = 0; $i<$length; $i++)
        {
            $video = $mediaArray[$i]->getMediafile();
            $videoDiv = explode(":",$video);
            if($videoDiv[0] == "https")
            {
                $videoUrl = $video;
                break;
            }
        }

        return $videoUrl;
        
    }


    public function ratingCalculation(Movies $fetchMovie):float
    {
        $totalrating = 0.0;
        $count = 0;
        foreach($fetchMovie->getReview() as $review)
        {
            $totalrating = $totalrating + (float)$review->getRating();
            $count++;
        }
        if($totalrating >0.0)
        {
            return $totalrating/$count;
        }
        else{
            return 0.0;
        }
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/media_galary/{id}",name="media_galary")
     */
    public function loadGalary(int $id)
    {
       
            $fetchMovie = $this->movieService->getMovie($id);
            $imageArray = $this->loadImages($fetchMovie);
            $videoUrl = $this->loadTrailer($fetchMovie);
            $videoArray = $this->loadVideo($fetchMovie);

            $path = "App\Controller\BaseController::loadGalary";
            $user = $this->getUser();
            $event = new MovieMediaEvent($user,$fetchMovie,$path);
            $this->eventDispatcher->dispatch($event,MovieMediaEvent::class);

            return $this->render('admin_movie/movie_galary.html.twig',[
                'fetchMovie' => $fetchMovie,
                'imageArray' =>$imageArray,
                'videoUrl' =>$videoUrl,
                'videoArray' => $videoArray
            ]);
    
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route("/logout",name="logout")
     */
    public function logOut()
    {
      throw new \LogicException("This method can not be blank");
      
      return $this->redirectToRoute("index_page");
    }
}



?>