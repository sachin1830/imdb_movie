<?php
namespace App\EventListener\UserEvent;

use App\Entity\User;
use App\Entity\Writers;
use Symfony\Contracts\EventDispatcher\Event;

class WriterEvent extends Event
{
    /**
     * @var Writers
     */
    private $writer;
    /**
     * @var User
     */
    private $user;
    private $path;

    function __construct(User $user,Writers $writer,string $path)
    {
        $this->writer = $writer;
        $this->user = $user;
        $this->path = $path;
    }

    public function getWriter(): Writers
    {
        return $this->writer;
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