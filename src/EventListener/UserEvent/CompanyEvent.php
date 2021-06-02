<?php
namespace App\EventListener\UserEvent;

use App\Entity\ProdutionCompanys;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class CompanyEvent extends Event
{
    /**
     * @var ProdutionCompanys
     */
    private $company;
    /**
     * @var User
     */
    private $user;
    private $path;

    function __construct(User $user,ProdutionCompanys $company,string $path)
    {
        $this->company = $company;
        $this->user = $user;
        $this->path = $path;
    }

    public function getCompany(): ProdutionCompanys
    {
        return $this->company;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPath()
    {
        return $this->path;
    }
}

?>