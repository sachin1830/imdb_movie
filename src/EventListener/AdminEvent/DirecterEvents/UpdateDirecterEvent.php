<?php

namespace App\EventListener\AdminEvent\DirecterEvents;

use App\Entity\Admin;
use App\Entity\Directers;
use Symfony\Contracts\EventDispatcher\Event;

class UpdateDirecterEvent extends Event
{
    /**
     * @var Directers
     */
    private $directer;

    private $admin;

    function __construct(Directers $directer,Admin $admin)
    {
        $this->directer = $directer;
        $this->admin = $admin;
    }

    public function getDirecter(): Directers
    {
        return $this->directer;
    }

    public function getAdmin(): Admin
    {
        return $this->admin;
    }
}

?>