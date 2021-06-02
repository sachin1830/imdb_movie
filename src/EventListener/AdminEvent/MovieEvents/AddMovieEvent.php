<?php
namespace App\EventListener\AdminEvent\MovieEvents;

use App\Entity\Admin;
use App\Entity\Movies;
use Symfony\Contracts\EventDispatcher\Event;

class AddMovieEvent extends Event
{
    /**
     * @var Movies
     */
    private $movie;
    private $admin;
    function __construct(Movies $movie,Admin $admin)
    {
        $this->movie = $movie;
        $this->admin = $admin;
    }

    public function getMovie(): Movies
    {
        return $this->movie;
    }

    public function getAdmin(): Admin
    {
        return $this->admin;
    }
}

?>