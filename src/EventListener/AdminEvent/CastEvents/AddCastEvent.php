<?php
namespace App\EventListener\AdminEvent\CastEvents;

use App\Entity\Admin;
use App\Entity\Casts;
use Symfony\Contracts\EventDispatcher\Event;

class AddCastEvent extends Event
{
    /**
     * @var Casts
     */
    private $cast;
    private $admin;
    function __construct(Casts $cast,Admin $admin)
    {
        $this->cast = $cast;
        $this->admin = $admin;
    }

    public function getCast(): Casts
    {
        return $this->cast;
    }

    public function getAdmin(): Admin
    {
        return $this->admin;
    }
}

?>