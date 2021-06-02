<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * MovieMedia
 *
 * @ORM\Table(name="movie_media", indexes={@ORM\Index(name="fk_movie_media_movies1_idx", columns={"movies_id"})})
 * @ORM\Entity
 */
class MovieMedia
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
     * @ORM\Column(name="mediafile", type="string", length=100, nullable=false)
     */
    private $mediafile;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Movies" ,inversedBy="movieMedia")
     */
    private $movies;

    public function __construct()
    {
        $this->movies=new ArrayCollection();
    }

    public function getId():int
    {
        return $this->id;
    }

    public function getMediafile():string
    {
        return $this->mediafile;
    }

    public function setMediafile(string $mediafile)
    {
        $this->mediafile = $mediafile;
    }

    public function getMoviesId()
    {
        return $this->movies;
    }

    public function setMoviesId(Movies $movies)
    {
        $this->movies = $movies;
    }

}
