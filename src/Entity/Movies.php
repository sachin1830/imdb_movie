<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Movies
 *
 * @ORM\Table(name="movies", uniqueConstraints={@ORM\UniqueConstraint(name="title_UNIQUE", columns={"title"})})
 * @ORM\Entity
 */
class Movies
{
    /**
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     *
     * @ORM\Column(name="poster", type="string", length=255, nullable=false)
     */
    private $poster;

    /**
     *
     * @ORM\Column(name="releaseyear", type="integer", nullable=false)
     */
    private $releaseyear;

    /**
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     *
     * @ORM\Column(name="runtime", type="integer", nullable=false)
     */
    private $runtime;

    /**
     *
     * @ORM\Column(name="moviebudget", type="integer", nullable=false)
     */
    private $moviebudget;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Casts", inversedBy="movies")
     * @ORM\JoinTable(name="casts_movies_index")
     */
    private $casts;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Categores", inversedBy="movies")
     * @ORM\JoinTable(name="categories_movies_index")
     */
    private $categores;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Countrys", inversedBy="movies")
     * @ORM\JoinTable(name="countrys_movies_index")
     */
    private $countrys;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Directers", inversedBy="movies")
     * @ORM\JoinTable(name="directers_movies_index")
     */
    private $directers;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Languages", inversedBy="movies")
     * @ORM\JoinTable(name="movies_language_index")
     */
    private $languages;

    /**
     *
     * @ORM\ManyToMany(targetEntity="ProdutionCompanys", inversedBy="movies")
     * @ORM\JoinTable(name="movies_prodution_companys_index")
     */
    private $produtionCompanys;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Writers", inversedBy="movies")
     * @ORM\JoinTable(name="movies_writers_index")
     */
    private $writers;

    /**
     * @ORM\OneToMany(targetEntity="MovieMedia", mappedBy="movies")
     */
    private $movieMedia;

    /**
     * @ORM\OneToMany(targetEntity="MovieReview", mappedBy="movies")
     */
    private $review;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->casts = new ArrayCollection();
        $this->categores = new ArrayCollection();
        $this->countrys = new ArrayCollection();
        $this->directers = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->produtionCompanys = new ArrayCollection();
        $this->writers = new ArrayCollection();
        $this->movieMedia=new ArrayCollection();
        $this->review = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getReleaseyear(): ?int
    {
        return $this->releaseyear;
    }

    public function setReleaseyear(int $releaseyear): self
    {
        $this->releaseyear = $releaseyear;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getRuntime(): ?int
    {
        return $this->runtime;
    }

    public function setRuntime(int $runtime): self
    {
        $this->runtime = $runtime;

        return $this;
    }

    public function getMoviebudget(): ?int
    {
        return $this->moviebudget;
    }

    public function setMoviebudget(int $moviebudget): self
    {
        $this->moviebudget = $moviebudget;

        return $this;
    }

    /**
     * @return Collection|Casts[]
     */
    public function getCasts(): Collection
    {
        return $this->casts;
    }

    public function addCast(Casts $cast): self
    {
        if (!$this->casts->contains($cast)) {
            $this->casts[] = $cast;
        }

        return $this;
    }
    public function removeCast(Casts $cast)
    {
        if ($this->casts->contains($cast)) {
            $this->casts->removeElement($cast);
        }
        return $this;
    }

    /**
     * @return Collection|Categores[]
     */
    public function getCategores(): Collection
    {
        return $this->categores;
    }

    public function addCategores(Categores $categore)
    {
        if (!$this->categores->contains($categore)) {
            $this->categores[] = $categore;
        }

        return $this;
    }
    public function removeCategores(Categores $categore)
    {
        if ($this->categores->contains($categore)) {
            $this->categores->removeElement($categore);
        }
        return $this;
    }


   
    /**
     * @return Collection|Countrys[]
     */
    public function getCountrys(): Collection
    {
        return $this->countrys;
    }

    public function addCountry(Countrys $country)
    {
        if (!$this->countrys->contains($country)) {
            $this->countrys[] = $country;
        }

        return $this;
    }

    public function removeCountry(Countrys $country)
    {
        if ($this->countrys->contains($country)) {
            $this->countrys->removeElement($country);
        }
        return $this;
    }

    
    /**
     * @return Collection|Directers[]
     */
    public function getDirecters(): Collection
    {
        return $this->directers;
    }

    public function addDirecter(Directers $directer): self
    {
        if (!$this->directers->contains($directer)) {
            $this->directers[] = $directer;
        }

        return $this;
    }

    public function removeDirecter(Directers $directer)
    {
        if ($this->directers->contains($directer)) {
            $this->directers->removeElement($directer);
        }
        return $this;
    }

    /**
     * @return Collection|Languages[]
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Languages $language): self
    {
        if (!$this->languages->contains($language)) {
            $this->languages[] = $language;
        }

        return $this;
    }

    public function removeLanguage(Languages $language)
    {
        if ($this->languages->contains($language)) {
            $this->languages->removeElement($language);
        }
        return $this;
    }


    /**
     * @return Collection|ProdutionCompanys[]
     */
    public function getProdutionCompanys(): Collection
    {
        return $this->produtionCompanys;
    }

    public function addProdutionCompany(ProdutionCompanys $produtionCompany): self
    {
        if (!$this->produtionCompanys->contains($produtionCompany)) {
            $this->produtionCompanys[] = $produtionCompany;
        }

        return $this;
    }

    public function removeProdutionCompany(ProdutionCompanys $produtionCompany)
    {
        if ($this->produtionCompanys->contains($produtionCompany)) {
            $this->produtionCompanys->removeElement($produtionCompany);
        }
        return $this;
    }

    /**
     * @return Collection|Writers[]
     */
    public function getWriters(): Collection
    {
        return $this->writers;
    }

    public function addWriter(Writers $writer): self
    {
        if (!$this->writers->contains($writer)) {
            $this->writers[] = $writer;
        }

        return $this;
    }

    public function removeWriters(Writers $writer)
    {
        if ($this->writers->contains($writer)) {
            $this->writers->removeElement($writer);
        }
        return $this;
    }

    /**
     * @return Collection|MovieMedia[]
     */
    public function getMovieMedia(): Collection
    {
        return $this->movieMedia;
    }

    public function addMovieMedia(MovieMedia $movieMedia): self
    {
        if (!$this->movieMedia->contains($movieMedia)) {
            $this->movieMedia[] = $movieMedia;
        }

        return $this;
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

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'poster' => $this->getPoster(),
            'description' => $this->getDescription(),
            'releaseyear' => $this->getReleaseyear(),
            'runtime' => $this->getRuntime(),
            'budget' => $this->getMoviebudget()
        ];
    }
}
