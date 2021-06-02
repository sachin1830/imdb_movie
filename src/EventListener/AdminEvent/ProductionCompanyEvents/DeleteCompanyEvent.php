<?php

namespace App\EventListener\AdminEvent\ProductionCompanyEvents;

use App\Entity\Admin;
use Symfony\Contracts\EventDispatcher\Event;

class DeleteCompanyEvent extends Event
{
    
    private $companyId;
    private $companyName;

    /**
     * @var Admin
     */
    private $admin;

    function __construct(int $companyId, string $companyName, Admin $admin)
    {
        $this->companyId = $companyId;
        $this->companyName = $companyName;
        $this->admin = $admin;
    }

    public function getCompanyId(): ?int
    {
        return $this->companyId;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function getAdmin(): Admin
    {
        return $this->admin;
    }
}

?>