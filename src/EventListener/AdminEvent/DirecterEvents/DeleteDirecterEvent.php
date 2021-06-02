<?php

namespace App\EventListener\AdminEvent\DirecterEvents;

use App\Entity\Admin;
use Symfony\Contracts\EventDispatcher\Event;

class DeleteDirecterEvent extends Event
{
    private $directerId;
    private $directerName;

    /**
     * @var Admin
     */
    private $admin;

    function __construct(int $directerId,string $directerName, Admin $admin)
    {
        $this->directerId = $directerId;
        $this->directerName = $directerName;
        $this->admin = $admin;
    }

    public function getDirecterId(): ?int
    {
        return $this->directerId;
    }

    public function getDirecterName(): ?string
    {
        return $this->directerName;
    }

    public function getAdmin(): Admin
    {
        return $this->admin;
    }
}

?>