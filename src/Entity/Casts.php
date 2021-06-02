<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Casts
 *
 * @ORM\Table(name="casts", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 * @ORM\Entity
 */
class Casts
{
    /**
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     *
     * @ORM\Column(name="gender", type="string", length=45, nullable=false)
     */
    private $gender;

    /**
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     *
     * @ORM\Column(name="height", type="string", length=45, nullable=false)
     */
    private $height;

    /**
     *
     * @ORM\Column(name="sign", type="string", length=45, nullable=false)
     */
    private $sign;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Movies", mappedBy="casts")
     */
    private $movies;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->movies = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(string $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getSign(): ?string
    {
        return $this->sign;
    }

    public function setSign(string $sign): self
    {
        $this->sign = $sign;

        return $this;
    }

    /**
     * @return Collection|Movies[]
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movies $movie): self
    {
        if (!$this->movies->contains($movie)) {
            $this->movies[] = $movie;
            $movie->addCast($this);
        }

        return $this;
    }

    public function toArray()
        {
            return [
                'id' => $this->getId(),
                'name' => $this->getName(),
                'gender' => $this->getGender(),
                'image' => $this->getImage(),
                'height' => $this->getHeight(),
                'desc' => $this->getDescription(),
                'sign' => $this->getSign()
            ];
        }
}
