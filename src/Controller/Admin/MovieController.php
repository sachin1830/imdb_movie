<?php

namespace App\Controller\Admin;

use App\Entity\Admin;
use App\Entity\MovieMedia;
use App\Entity\Movies;
use App\EventListener\AdminEvent\AdminLoginEvent;
use App\EventListener\AdminEvent\MovieEvents\AddMovieEvent;
use App\EventListener\AdminEvent\MovieEvents\DeleteMovieEvent;
use App\EventListener\AdminEvent\MovieEvents\UpdateMovieEvent;
use App\Helper\MovieHelper;
use App\Services\Cast\CastService;
use App\Services\Category\CategoryService;
use App\Services\Country\CountryService;
use App\Services\Directer\DirecterService;
use App\Services\Language\LanguageService;
use App\Services\Movie\MovieService;
use App\Services\ProductionCompany\ProductionCompnayService;
use App\Services\Writer\WriterService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MovieController extends AbstractController
{
    /**
     * @var MovieService
     */
    private $movieService;
     /**
     * @var PaginatorInterface
     */
    private $paginator;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var DirecterService
     */
    private $directerService;
    /**
     * @var WriterService
     */
    private $writerService;
    /**
     * @var CastService
     */
    private $castService;
    /**
     * @var ProductionCompnayService
     */
    private $productionService;
    /**
     * @var CategoryService
     */
    private $categoryService;
    /**
     * @var LanguageService
     */
    private $languageService;
    /**
     * @var CountryService
     */
    private $countryService;
    /**
     * @var MovieHelper
     */
    private $helper;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

 

    function __construct(MovieService $movieService, PaginatorInterface $paginator
    ,EntityManagerInterface $entityManager, DirecterService $directerService
    ,WriterService $writerService, CastService $castService, 
    ProductionCompnayService $productionService , CategoryService $categoryService
    , LanguageService $languageService, CountryService $countryService, 
    MovieHelper $helper,  EventDispatcherInterface $eventDispatcher )
    {
        $this->movieService = $movieService;
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
        $this->directerService = $directerService;
        $this->writerService = $writerService;
        $this->castService = $castService;
        $this->productionService = $productionService;
        $this->categoryService = $categoryService;
        $this->languageService =$languageService;
        $this->countryService = $countryService;
        $this->helper = $helper;
        $this->eventDispatcher =$eventDispatcher;
    }
    private $check=0;
    /**
     * @Route("/admin",name="admin_panel")
     * @IsGranted("ROLE_ADMIN")
     */
    public function adminPanel(Request $request,
                                SessionInterface $session):Response
    {
            $fetchMovies = $this->movieService->getAllMovies();

            $pagination = $this->paginator->paginate(
                $fetchMovies, /* query NOT result */
                $request->query->getInt('page', 1)/*page number*/,
                5/*limit per page*/
            );

            if(!$session->has("loginadmin"))
            {
                /**
                 * @var Admin
                 */
                $session->set("loginadmin","Logindone");
                $admin = $this->getUser();
                $event = new AdminLoginEvent($admin);
                $this->eventDispatcher->dispatch($event,AdminLoginEvent::NAME);
            }
            return $this->render("admin/adminpanel.html.twig",[
                'fetch_movive' =>$pagination
            ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/desc",name="admin_panel_desc")
     */
    public function moviePanelDesc(Request $request)
    {
        $fetchMovies = $this->movieService->getAllMoviesDESC();

        $pagination = $this->paginator->paginate(
            $fetchMovies, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            5/*limit per page*/
        );

        return $this->render("admin/adminpanel.html.twig",[
            'fetch_movive' =>$pagination
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/addmovie",name="add_movie")
     */
    public function addMovies():Response
    {
        if(isset($_POST['submit']))
        {
            $movie = new Movies();
            $title = $_POST['title'];
            $runtime = $_POST['runtime'];
            $year = $_POST['year'];
            $budget = $_POST['budget'];
            $poster = $_FILES['poster'];
            $description = $_POST['desc'];
            $directers = $_POST['directer'];
            $writers = $_POST['writer'];
            $casts = $_POST['cast'];
            $language = $_POST['language'];
            $category = $_POST['category'];
            $country = $_POST['country'];
            $company = $_POST['company'];

            $filename = $poster['name'];
            $filetemp = $poster['tmp_name'];
            $destinationPath = $this->getParameter('kernel.project_dir')
                                    .'/public/upload/'.$filename;
            //This method  move the file to destination folder
            move_uploaded_file($filetemp,$destinationPath);
           
            //pass the data to this method 
            $movie = $this->setMovieData($movie,$title,$runtime,$year,$budget,
            $description,$directers,$writers,$casts,$language,$category,
            $country,$company);

            $movie->setPoster($filename);
            $this->entityManager->persist($movie);
            $this->entityManager->flush();
            $this->addFlash('notice','Movie Successfully added .');

            $admin = $this->getUser();
            $event = new AddMovieEvent($movie,$admin);
            $this->eventDispatcher->dispatch($event,AddMovieEvent::class);

            return $this->redirectToRoute('admin_panel');
        }

        $fetchCountrys = $this->countryService->getAllCountry();
        $fetchLanguages = $this->languageService->getAllLanguage();
        $fetchCategory = $this->categoryService->getAllCategory();
        $fetchDirecter = $this->directerService->getAllDirecters();
        $fetchWriters = $this->writerService->getAllWriters();
        $fetchCasts = $this->castService->getAllCasts();
        $fetchCompany = $this->productionService->getAllCompanyes();

        return $this->render("admin_movie/addmovie.html.twig",[
            'fetchCountry' =>$fetchCountrys ,
            'fetchLanguage'=>$fetchLanguages ,
            'fetchCategory' => $fetchCategory,
            'fetchDirecter' =>$fetchDirecter,
            'fetchWriters' =>$fetchWriters,
            'fetchCasts'=>$fetchCasts,
            'fetchCompany' =>$fetchCompany
        ]);
        
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/update_movie/{id}",name="update_movie")
     */
    public function updateMovie(int $id):Response
    {
        $fetchMovie = $this->movieService->getMovie($id);

        if(!$fetchMovie)
        {
            throw $this->createNotFoundException(
                'No directer found'
            );
        }
        if(isset($_POST['submit']))
        {
            $title = $_POST['title'];
            $runtime = $_POST['runtime'];
            $year = $_POST['year'];
            $budget = $_POST['budget'];
            $poster = $_FILES['poster'];
            $description = $_POST['desc'];
            $directers = $_POST['directer'];
            $writers = $_POST['writer'];
            $casts = $_POST['cast'];
            $language = $_POST['language'];
            $category = $_POST['category'];
            $country = $_POST['country'];
            $company = $_POST['company'];

            $fetchMovie = $this->setMovieData($fetchMovie,$title,$runtime,$year,
                $budget,$description,$directers,$writers,$casts,$language
                ,$category,$country,$company);

            $filename = $poster['name'];
            if($filename == "")
            {
                $fetchMovie->setPoster($fetchMovie->getPoster());
            }
            else
            {
                $filetemp = $poster['tmp_name'];
                $destinationPath = $this->getParameter('kernel.project_dir')
                                        .'/public/upload/'.$filename;
                move_uploaded_file($filetemp,$destinationPath);
                $fetchMovie->setPoster($filename);
            }
            $this->entityManager->persist($fetchMovie);
            $this->entityManager->flush();
            $this->addFlash('notice','Movie successfully updated .');

            //here event is seting when the movie will update this event will fire

            $admin = $this->getUser();
            $event = new UpdateMovieEvent($fetchMovie,$admin);
            $this->eventDispatcher->dispatch($event,UpdateMovieEvent::class);
            
            return $this->redirectToRoute("admin_panel");
        }

        $fetchCountrys = $this->countryService->getAllCountry();
        $fetchLanguages = $this->languageService->getAllLanguage();
        $fetchCategory = $this->categoryService->getAllCategory();
        $fetchDirecter = $this->directerService->getAllDirecters();
        $fetchWriters = $this->writerService->getAllWriters();
        $fetchCasts = $this->castService->getAllCasts();
        $fetchCompany = $this->productionService->getAllCompanyes();

        return $this->render("admin_movie/movie_update.html.twig",[
            //$fetch_movie->getCasts()[1]->getId()
                'fetchMovie' =>$fetchMovie,
                'fetchCountry' =>$fetchCountrys,
                'fetchLanguage' =>$fetchLanguages,
                'fetchCategory' =>$fetchCategory,
                'fetchDirecter' =>$fetchDirecter,
                'fetchWriter'=>$fetchWriters,
                'fetchCast' =>$fetchCasts,
                'fetchCompany' =>$fetchCompany
            ]);
    }
     //This method create movie object and the data

    public function setMovieData(Movies $movie,$title,$runtime,$year,$budget,
                $description,$directers,$writers,$casts,$language,$category
                ,$country,$company):Movies
    {
        $movie->setTitle($title);
        $movie->setDescription($description);
        $movie->setReleaseyear($year);
        $movie->setRuntime($runtime);
        $movie->setReleaseyear($year);
        $movie->setMoviebudget($budget);

        $movie = $this->helper->setCasts($movie,$casts);
        $movie = $this->helper->setDirecters($movie,$directers);
        $movie = $this->helper->setWriters($movie,$writers);
        $movie = $this->helper->setCompanys($movie,$company);
        $movie = $this->helper->setLanguages($movie,$language);
        $movie = $this->helper->setCountrys($movie,$country);
        $movie = $this->helper->setCategorys($movie,$category);
        
        return $movie;
    }
    
     /**
      * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/delete_movie/{id}",name="delete_movie")
     */
    public function DeleteMovie(int $id):Response
    {
        $fetchMovie = $this->movieService->getMovie($id);
        $movieid = $fetchMovie->getId();
        $movieTitle = $fetchMovie->getTitle();

        if($fetchMovie)
        {
            $this->entityManager->remove($fetchMovie);
        }
        else
        {
            echo"No data found";
        }
        $this->entityManager->flush();
        $this->addFlash('notice','Movie successfully deleted .');
        //here event is seting when the movie will delete this event will fire
        $admin = $this->getUser();
        $event = new DeleteMovieEvent($movieid,$movieTitle,$admin);
        $this->eventDispatcher->dispatch($event,DeleteMovieEvent::class);

        return $this->redirectToRoute('admin_panel');
    }

     /**
      * @IsGranted("ROLE_ADMIN")
     * @Route("/admin/addmedia/{id}",name="add_media")
     */
    public function addMedia(int $id)
    {
        $fetchMovie = $this->movieService->getMovie($id);

        if(isset($_POST['submit']))
        {
            $mediafile = $_FILES['file'];
            $video = $_POST['video'];

            $filename = $mediafile['name'];
            
            $mediaFileDiv = explode('.',$filename);
            $mediaFileLowerExt=strtolower(end($mediaFileDiv));
            $mediaExts = array('jpg', 'png', 'jpeg' , 'mkv' , 'mp4');
            $videoFileDiv = explode(':',$video);
            $media = new MovieMedia();
            if($filename == "" && $video == "")
            {
                $this->addFlash("media_error","You can not leave all field empty");
            }
            else{
                if(!$filename =="" && !$video=="")
                {
                    $this->addFlash("media_error","Both media can not given at 
                                    one time");
                            return $this->render("admin_movie/add_media.html.twig",[
                                'fetchMovie' => $fetchMovie
                            ]);
                }
                else{
                if(!$filename == "")
                {
                    if(in_array($mediaFileLowerExt,$mediaExts))
                    {
                        $filetemp = $mediafile['tmp_name'];

                        $destinationPath = $this->getParameter('kernel.project_dir')
                        .'/public/upload/'.$filename;

                        move_uploaded_file($filetemp,$destinationPath);

                        $media->setMediafile($filename);
                        $media->setMoviesId($fetchMovie);
                        $this->entityManager->persist($media);
                        $this->entityManager->flush();
                        $this->addFlash("media","Media file successfully added");
                        return $this->redirectToRoute("admin_panel");

                    }
                    else{
                        $this->addFlash("media_error","wrong format image");
                        return $this->render("admin_movie/add_media.html.twig",[
                            'fetchMovie' => $fetchMovie
                        ]);
                    }
                }
                if(!$video == "")
                {
                    if($videoFileDiv[0] == "https")
                        {
                            $media->setMediafile($video);
                            $media->setMoviesId($fetchMovie);
                            $this->entityManager->persist($media);
                            $this->entityManager->flush();
                            $this->addFlash("media","Media file successfully added");
                            return $this->redirectToRoute("admin_panel");
                        }
                        else
                        {
                            $this->addFlash("media_error","wrong format video url");
                            return $this->render("admin_movie/add_media.html.twig",[
                                'fetchMovie' => $fetchMovie
                            ]);
                        }
                }
                }
            }
        }
        return $this->render("admin_movie/add_media.html.twig",[
            'fetchMovie' => $fetchMovie
        ]);

    }

     /**
      * @IsGranted("ROLE_USER")
     * @Route("/movie/search",name="search_movie")
     */
    public function searchMovie()
    { 
        if(isset($_POST['submit']))
        {
            $movieName = $_POST['moviename'];

            $fetchMovie = $this->movieService->getMovieByName($movieName);
            if($fetchMovie)
            {
                return $this->redirectToRoute("movie_details",[
                    'id' => $fetchMovie->getId()
                ]);
            }
            else
            {
                return $this->redirectToRoute("load_movies");
            }
        }
        else
        {
            return $this ->redirectToRoute("load_movies");
        }
    }
}

?>