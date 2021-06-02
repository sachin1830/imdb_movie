<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Admin
 *
 * @ORM\Table(name="admin", uniqueConstraints={@ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"})})
 * @ORM\Entity
 */
class Admin implements UserInterface
{
    /**
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false ,  unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="password", type="string", length=100, nullable=false)
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */

    private $role;

    public function getId():int
    {
        return $this->id;
    }

    public function getName():string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getEmail():string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getPassword():string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function setRole(array $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getRoles(): array
    {
        $roles= $this->role;

        $roles[] ="ROLE_USER";

        return array_unique($roles);
    }

    public function getSalt()
    {
        
    }

    public function getUsername()
    {
        return  (string) $this->email;
    }

    public function eraseCredentials()
    {
       
    }

}
