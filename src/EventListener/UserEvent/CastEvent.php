<?php
namespace App\EventListener\UserEvent;

use App\Entity\Casts;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class CastEvent extends Event
{
    /**
     * @var Casts
     */
    private $cast;
    /**
     * @var User
     */
    private $user;
    private $path;

    function __construct(User $user,Casts $cast,string $path)
    {
        $this->cast = $cast;
        $this->user = $user;
        $this->path = $path;
    }

    public function getCast(): Casts
    {
        return $this->cast;
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