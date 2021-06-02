<?php

namespace App\EventListener\AdminEvent\WriterEvents;

use App\Entity\Admin;
use App\Entity\Writers;
use Symfony\Contracts\EventDispatcher\Event;

class DeleteWriterEvent extends Event
{
    private $writerId;
    private $writerName;

    /**
     * @var Admin
     */
    private $admin;

    function __construct(int $writerId,string $writerName, Admin $admin)
    {
        $this->writerId = $writerId;
        $this->writerName = $writerName;
        $this->admin = $admin;
    }

    public function getWriterId(): ?int
    {
        return $this->writerId;
    }

    public function getWriterName(): string
    {
        return $this->writerName;
    }

    public function getAdmin(): Admin
    {
        return $this->admin;
    }
}

?>