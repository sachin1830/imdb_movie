<?php
namespace App\EventListener\AdminEvent\MovieEvents;

use App\Entity\Admin;
use Symfony\Contracts\EventDispatcher\Event;

class DeleteMovieEvent extends Event
{
    
    private $movieId;
    private $moveTitle;
    private $admin;
    function __construct(int $movieId,string $movieTitle,Admin $admin)
    {
        $this->$movieId = $movieId;
        $this->moveTitle = $movieTitle;
        $this->admin = $admin;
    }
    
    public function getMovieId(): ?int
    {
        return $this->movieId;
    }

    public function getMoveTitle(): string
    {
        return $this->moveTitle;
    }

    public function getAdmin(): Admin
    {
        return $this->admin;
    }
}

?>