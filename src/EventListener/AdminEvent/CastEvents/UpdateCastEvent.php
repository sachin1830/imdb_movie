<?php
namespace App\EventListener\AdminEvent\CastEvents;

use App\Entity\Admin;
use App\Entity\Casts;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateCastEvent extends Event
{
    /**
     * @var Casts
     */
    private $cast;
    private $admin;
    private $method;
    private $path;
    function __construct(Casts $cast,Admin $admin,string $method , string $path)
    {
        $this->cast = $cast;
        $this->admin = $admin;
        $this->method = $method;
        $this->path = $path;
    }

    public function getCast(): Casts
    {
        return $this->cast;
    }

    public function getAdmin(): Admin
    {
        return $this->admin;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}

?>