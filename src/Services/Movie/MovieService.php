<?php
namespace App\Services\Movie;

use App\Entity\Movies;
use App\Repository\MovieRepository;
use Exception;

class MovieService implements MovieServiceInterface
{
    /**
     * @var MovieRepository
     */
    private $movieRepository;

    function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function getMovie(int $id): ?Movies
    {
        $movie = $this->movieRepository->find($id);
        return $movie;
    }

    public function getAllMovies():array
    {
        $movies = $this->movieRepository->findAll();

        return $movies;
    }

    public function getAllMoviesDESC():array
    {
        $moviesdesc = $this->movieRepository->findByDESC();

        return $moviesdesc;
    }
    public function getMovieByName(string $name): ?Movies
    {
        $movie = $this->movieRepository->findOneBy(['title' => $name]);
        return $movie;
    }

   
}

?>