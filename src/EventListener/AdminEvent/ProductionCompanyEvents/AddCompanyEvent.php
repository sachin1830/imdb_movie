<?php

namespace App\EventListener\AdminEvent\ProductionCompanyEvents;

use App\Entity\Admin;
use App\Entity\ProdutionCompanys;
use Symfony\Contracts\EventDispatcher\Event;

class AddCompanyEvent extends Event
{
    /**
     * @var ProdutionCompanys
     */
    private $company;

    /**
     * @var Admin
     */
    private $admin;

    function __construct(ProdutionCompanys $company, Admin $admin)
    {
        $this->company = $company;
        $this->admin = $admin;
    }

    public function getCompany(): ProdutionCompanys
    {
        return $this->company;
    }

    public function getAdmin(): Admin
    {
        return $this->admin;
    }
}

?>