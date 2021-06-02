<?php
namespace App\Controller\RestApi;

use App\Entity\MovieMedia;
use App\Entity\Movies;
use App\Helper\MovieHelper;
use App\Repository\MovieMediaRepository;
use App\Services\Movie\MovieService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class MovieRestController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MovieService
     */
    private $movieService;
    /**
     * @var MovieHelper
     */
    private $helper;
    function __construct(EntityManagerInterface $entityManager,
    MovieService $movieService, MovieHelper $helper)
    {
        $this->entityManager = $entityManager;
        $this->movieService = $movieService;
        $this->helper = $helper;
    }
    /**
     * @Route("/movie/",name="api_add_movie",methods={"POST"})
     */
    public function addMovie(Request $request):JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $movie = new Movies();
        try {
          $movie = $this->setData($movie,$data);
        } catch (Exception $e) {
          return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }
       
        $this->entityManager->persist($movie);
        $this->entityManager->flush();

       return new JsonResponse(['status' => 'movie created!'], 
                                Response::HTTP_CREATED);
    }

    //In this method we are getting movie data by movie id
    /**
     * @Route("/movie/{id}",name="api_movie_cast",methods={"GET"})
     */
    public function getMovie($id):JsonResponse
    {
        try {
            $movie = $this->movieService->getMovie($id);

            if(!$movie)
            {
                throw new Exception("Invalid id, movie not found !!");   
            }
        } catch (\Exception $e) {
          echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }
       
       $casts = $this->getCastsArr($movie);
       $categorys = $this->getCategoresArr($movie);
       $directers = $this->getDirectersArr($movie);
       $languages = $this->getLanguagesArr($movie);
       $countries = $this->getCountrysArr($movie);
       $productioncompany = $this->getProdutionCompanysArr($movie);
       $writers = $this->getWritersArr($movie);
       $mediafiles = $this->getMovieMediaArr($movie);
       $reviews = $this->getReviewArr($movie);
       
       $data = [ 
           'id' => $movie->getId(),
           'title' => $movie->getTitle(),
           'poster' => $movie->getPoster(),
           'year' => $movie->getReleaseyear(),
           'desc' => $movie->getDescription(),
           'runtime' => $movie->getRuntime(),
           'budget' => $movie->getMoviebudget(), 
           'cast' => $casts,
           'categorys' => $categorys,
           'directers' => $directers,
           'languages' => $languages,
           'countries' => $countries,
           'productioncompany' => $productioncompany,
           'writers' => $writers,
           'mediafiles' => $mediafiles ,
           'reviews' => $reviews
       ];
       return new JsonResponse($data, Response::HTTP_OK);
    }

    //This method return the take movie and return cast in array format
    public function getCastsArr(Movies $movie):array
    {
        $casts = [];
        foreach ($movie->getCasts() as $cast) {

            $casts[] =[
              'id' => $cast->getId(),
              'name' => $cast->getName()
            ];
         }
         return $casts;
    }
    //This method return categorys in array
    public function getCategoresArr(Movies $movie):array
    {
        $categorys = [];
        foreach ($movie->getCategores() as $category) {

            $categorys[] =[
              'id' => $category->getId(),
              'name' => $category->getName()
            ];
         }

         return $categorys;
    }
    //This method return directers in array
    public function getDirectersArr(Movies $movie):array
    {
        $directers = [];
        foreach ($movie->getDirecters() as $directer) {

            $directers[] =[
              'id' => $directer->getId(),
              'name' => $directer->getName()
            ];
         }

         return $directers;
    }
    //This method return language in array
    public function getLanguagesArr(Movies $movie):array
    {
        $languages = [];
        foreach ($movie->getLanguages() as $language) {

            $languages[] =[
              'id' => $language->getId(),
              'name' => $language->getName()
            ];
         }
         
         return $languages;
    }
    //This method return country in array
    public function getCountrysArr(Movies $movie):array
    {
        $countries = [];
        foreach ($movie->getCountrys() as $country) {
    
            $countries[] =[
              'id' => $country->getId(),
              'name' => $country->getName()
            ];
         }
         return $countries;
    }
    //This method return productioncompanyes array
    public function getProdutionCompanysArr(Movies $movie):array
    {
        $productioncompany = [];
        foreach ($movie->getProdutionCompanys() as $company) {

            $productioncompany[] =[
              'id' => $company->getId(),
              'name' => $company->getName()
            ];
         }
         return $productioncompany;
    }
    //This method return writer array
    public function getWritersArr(Movies $movie):array
    {
        $writers = [];
        foreach ($movie->getWriters() as $writer) {

            $writers[] =[
              'id' => $writer->getId(),
              'name' => $writer->getName()
            ];
         }
         return $writers;
    }
    //This method return moviemedia array
    public function getMovieMediaArr(Movies $movie):array
    {
        $mediafiles = [];
        foreach ($movie->getMovieMedia() as $media) {

            $mediafiles[] =[
              'id' => $media->getId(),
              'file' => $media->getMediafile()
            ];
         }
         return $mediafiles;
    }
    //This method return review array
    public function getReviewArr(Movies $movie):array
    {
        $reviews = [];
        foreach ($movie->getReview() as $review) {
           
            $reviews[] =[
              'id' => $review->getId(),
              'rating' => $review->getRating(),
              'review' => $review->getReview()
            ];
         }
         return $reviews;
    }

    /**
     * @Route("/movies/",name="api_get_movies",methods={"GET"})
     */
    public function getMovies()
    {
       $movies = $this->movieService->getAllMovies();

       foreach($movies as $movie)
       {
        $data[]= [ 
            'id' => $movie->getId(),
           'title' => $movie->getTitle(),
           'poster' => $movie->getPoster(),
           'year' => $movie->getReleaseyear(),
           'desc' => $movie->getDescription(),
           'runtime' => $movie->getRuntime(),
           'budget' => $movie->getMoviebudget()
        ];
       }
       return new JsonResponse($data, Response::HTTP_OK);
    }

     /**
     * @Route("/movie/{id}",name="api_update_movie",methods={"PUT"})
     */
    public function updateMovie(int $id ,Request $request):JsonResponse
    {
        $data = json_decode($request-> getContent(),true);
        try {
          $movie = $this->movieService->getMovie($id);

          if(!$movie)
          {
              throw new Exception("Invalid id, movie not found !!");   
          }
        } catch (\Exception $e) {
          echo "Error : ".$e->getMessage();
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }

        $movie=$this->setData($movie,$data);
        $this->entityManager->flush();
        return new JsonResponse($movie->toArray(), Response::HTTP_OK);
    }

     /**
     * @Route("/movie/{id}",name="api_delete_movie",methods={"DELETE"})
     */
    public function deleteMovie(int $id):JsonResponse
    {
        try {
          $movie = $this->movieService->getMovie($id);

          if(!$movie)
          {
              throw new Exception("Invalid id, movie not found !!");   
          }
        } catch (\Exception $e) {
          echo "Error : ".$e->getMessage();
          
            return new JsonResponse(['status' => 'Not found!'], 
            Response::HTTP_NOT_FOUND);
        }
          
        $this->entityManager->remove($movie);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'movie deleted'], 
                        Response::HTTP_NO_CONTENT);
    }

    //this function used for setting the value of cast
    public function setData(Movies $movie, $data):Movies
    {
        $title = $data['title'];
        $poster = $data['poster'];
        $releaseyear = $data['releaseyear'];
        $desc = $data['description'];
        $runtime = $data['runtime'];
        $moviebudget = $data['moviebudget'];
        $movieCast = $data['casts'];
        $movieDirecter = $data['directers'];
        $movieWriter = $data['writers'];
        $movieLanguage = $data['language'];
        $movieCountry = $data['country'];
        $movieCategory = $data['category'];
        $movieProduction = $data['company'];

        $movie->setTitle($title);
        $movie->setPoster($poster);
        $movie->setDescription($desc);
        $movie->setReleaseyear($releaseyear);
        $movie->setRuntime($runtime);
        $movie->setMoviebudget($moviebudget);
        try {
            if($title == "" || $poster == "" || $releaseyear = "" || $desc == "" 
            || $runtime == "" || $moviebudget == "") 
            {
              throw new Exception("Any value can not be null");
              
            }
        } catch (\Exception $e) {
          echo "Error : ".$e->getMessage();
          return new JsonResponse(['status' => 'Not found!'], 
          Response::HTTP_BAD_REQUEST);
        }
        
        try {
          $movie = $this->helper->setCasts($movie,$movieCast);
        } catch (Exception $e) {
          echo "Error : ".$e->getMessage();
          throw new Exception();
          
        }

        try {
          $movie = $this->helper->setDirecters($movie,$movieDirecter);
        } catch (\Exception $e) {
          echo "Error : ".$e->getMessage();
          throw new Exception();
        }
        try {
          $movie = $this->helper->setWriters($movie,$movieWriter);

        } catch (Exception $e) {
          echo "Error : ".$e->getMessage();
          throw new Exception();
        }

        try {
          $movie = $this->helper->setLanguages($movie,$movieLanguage);
        } catch (\Exception $e) {
          echo "Error : ".$e->getMessage();
          throw new Exception();
        }

        try {
          $movie = $this->helper->setCategorys($movie,$movieCategory);
        } catch (\Exception $e) {
          echo "Error : ".$e->getMessage();
          throw new Exception();
        }

        try {
          $movie = $this->helper->setCountrys($movie,$movieCountry);
        } catch (\Exception $e) {
          echo "Error : ".$e->getMessage();
          throw new Exception();
        }

        try {
          $movie = $this->helper->setCompanys($movie,$movieProduction);
        } catch (\Exception $e) {
          echo "Error : ".$e->getMessage();
          throw new Exception();
        }

        return $movie;
    }

    /**
     * @Route("/moviemedia/",name="api_add_media",methods={"POST"})
     */
    public function addMovieMedia(Request $request):JsonResponse
    {
      $data = json_decode($request->getContent(), true);

      $movieid = $data['movieid'];
      $file = $data['mediafile'];

      try {
        $movie = $this->movieService->getMovie($movieid);

        if(!$movie)
        {
            throw new Exception("Invalid id, movie not found !!");   
        }
      } catch (\Exception $e) {
        echo "Error : ".$e->getMessage();
          return new JsonResponse(['status' => 'Not found!'], 
          Response::HTTP_NOT_FOUND);
      }
      $movieMedia = new MovieMedia();
      $movieMedia->setMediafile($file);
      $movieMedia->setMoviesId($movie);

      $this->entityManager->persist($movieMedia);
      $this->entityManager->flush();

      return new JsonResponse(['status' => 'movie media added!'], 
      Response::HTTP_CREATED);

    }
     /**
     * @Route("/moviemedia/{id}",name="api_delete_media",methods={"DELETE"})
     */
    public function deleteMovieMedia(int $id,
                                 MovieMediaRepository $repo)
    {
       $movieMedia = $repo->find($id);
       if($movieMedia)
       {
         $this->entityManager-> remove($movieMedia); 
       }
       $this->entityManager->flush();

        return new JsonResponse(['status' => 'movie media deleted'], 
                        Response::HTTP_NO_CONTENT);

    }
}

?>