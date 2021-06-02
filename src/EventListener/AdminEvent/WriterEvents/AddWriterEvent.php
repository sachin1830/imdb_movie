<?php

namespace App\EventListener\AdminEvent\WriterEvents;

use App\Entity\Admin;
use App\Entity\Writers;
use Symfony\Contracts\EventDispatcher\Event;

class AddWriterEvent extends Event
{
    /**
     * @var Writers
     */
    private $writer;

    /**
     * @var Admin
     */
    private $admin;

    function __construct(Writers $writer, Admin $admin)
    {
        $this->writer = $writer;
        $this->admin = $admin;
    }

    public function getWriter(): Writers
    {
        return $this->writer;
    }

    public function getAdmin(): Admin
    {
        return $this->admin;
    }
}

?>