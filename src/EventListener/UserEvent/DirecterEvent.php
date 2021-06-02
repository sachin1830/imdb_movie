<?php
namespace App\EventListener\UserEvent;

use App\Entity\Directers;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class DirecterEvent extends Event
{
    /**
     * @var Directers
     */
    private $directer;
    /**
     * @var User
     */
    private $user;
    private $path;

    function __construct(User $user,Directers $directer,string $path)
    {
        $this->directer = $directer;
        $this->user = $user;
        $this->path = $path;
    }

    public function getDirecter(): Directers
    {
        return $this->directer;
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