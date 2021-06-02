<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"})})
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=50, nullable=false, unique= true)
     */
    private $password;

     /**
     * @ORM\OneToMany(targetEntity="MovieReview", mappedBy="user")
     */
    private $review;

    /**
     * @ORM\Column(type="json")
     */
    private $role;


    public function __construct()
    {
       $this->review=new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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


    /**
     * @return Collection|MovieReview[]
     */
    public function getReview(): Collection
    {
        return $this->review;
    }

    public function addReview(MovieReview $review): self
    {
        if (!$this->review->contains($review)) {
            $this->review[] = $review;
        }

        return $this;
    }

    public function removeReview(MovieReview $review): self
    {
       $this->review->removeElement($review);

        return $this;
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

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            "name" =>$this->getName(),
            "email" => $this->getEmail(),
            "password" =>  $this->getPassword()
        ];
    }
  

}
