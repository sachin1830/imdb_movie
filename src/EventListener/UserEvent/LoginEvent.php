<?php
namespace App\EventListener\UserEvent;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class LoginEvent extends Event
{
    /**
     * @var User
     */
    private $user;
    private $path;

    function __construct(User $user, string $path)
    {
        $this->user = $user;
        $this->path = $path;
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