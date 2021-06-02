<?php
namespace App\EventListener\AdminEvent\CastEvents;

use App\Entity\Admin;
use Symfony\Contracts\EventDispatcher\Event;

class DeleteCastEvent extends Event
{

    private $castId;
    private $castName;
    private $admin;
    function __construct(int $castId ,string $castName,Admin $admin)
    {
        $this->castId = $castId;
        $this->castName = $castName;
        $this->admin = $admin;
    }

    public function getCastID(): ?int
    {
        return $this->castId;
    }
    public function getCastName(): string
    {
        return $this->castName;
    }

    public function getAdmin(): Admin
    {
        return $this->admin;
    }
}

?>