<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * MovieReview
 *
 * @ORM\Table(name="movie_review", indexes={@ORM\Index(name="fk_movie_review_user1_idx", columns={"user_id"}), @ORM\Index(name="fk_movie_review_movies1_idx", columns={"movies_id"})})
 * @ORM\Entity
 */
class MovieReview
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
     * @var string|null
     *
     * @ORM\Column(name="rating", type="string", length=45, nullable=true)
     */
    private $rating;

    /**
     * @var string|null
     *
     * @ORM\Column(name="review", type="text", length=65535, nullable=true)
     */
    private $review;

    /**
     * @ORM\ManyToOne(targetEntity="Movies" ,inversedBy="review")
     */
    private $movies;

    /**
     * @ORM\ManyToOne(targetEntity="User" ,inversedBy="review")
     */
    private $user;


    public function __construct()
    {
       $this->movies= new ArrayCollection();
       $this->user=new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(?string $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReview(): ?string
    {
        return $this->review;
    }

    public function setReview(?string $review): self
    {
        $this->review = $review;

        return $this;
    }

    public function getMovies()
    {
        return $this->movies;
    }

    public function setMovies(Movies $movies): self
    {
        $this->movies = $movies;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }


}
