<?php
namespace App\Services\Movie;

use App\Entity\Movies;

interface MovieServiceInterface 
{
    public function getAllMovies():array;
    public function getMovie(int $id): ?Movies;
    public function getAllMoviesDESC():array;
    public function getMovieByName(string $name): ?Movies;
}

?>