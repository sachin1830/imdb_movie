<?php
namespace App\EventListener\AdminEvent;

use App\Entity\Admin;
use Symfony\Contracts\EventDispatcher\Event;

class AdminLoginEvent extends Event
{
    const NAME = "Admin_Login";

    private $count=0;

    /**
     * @var Admin
     */
    private $admin;
    function __construct(Admin $admin)
    {
        $this->admin = $admin;
        dump($this->count++);
    }

    public function getAdmin(): Admin
    {
        return $this->admin;
    }

    
}

?>