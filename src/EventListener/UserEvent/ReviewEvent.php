<?php
namespace App\EventListener\UserEvent;

use App\Entity\Movies;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class ReviewEvent extends Event
{
    private $review;
    /**
     * @var User
     */
    private $user;
    private $path;
    /**
     * @var Movies
     */
    private $movies;

    function __construct(User $user,Movies $movies, string $review,string $path)
    {
        $this->review = $review;
        $this->user = $user;
        $this->path = $path;
        $this->movies = $movies;
    }

    public function getMovie(): Movies
    {
        return $this->movies;
    }
    
    public function getReview(): string
    {
        return $this->review;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPath()
    {
        return $this->path;
    }
}

?>