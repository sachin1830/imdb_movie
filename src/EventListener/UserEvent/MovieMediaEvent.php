<?php
namespace App\EventListener\UserEvent;

use App\Entity\Movies;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class MovieMediaEvent extends Event
{
    /**
     * @var Movies
     */
    private $movie;
    /**
     * @var User
     */
    private $user;
    private $path;

    function __construct(User $user,Movies $movie,string $path)
    {
        $this->movie = $movie;
        $this->user = $user;
        $this->path = $path;
    }

    public function getMovie(): Movies
    {
        return $this->movie;
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