<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AdminLogRepository::class)
 * @ORM\Table(name="admin_log", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})})
 */
class AdminLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $date_time;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $log;
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $path;
     /**
     * @ORM\Column(type="string", length=45)
     */
    private $method;
    
    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateTime(): ?string
    {
        return $this->date_time;
    }

    public function setDateTime(string $date_time): self
    {
        $this->date_time = $date_time;

        return $this;
    }

    public function getLog(): ?string
    {
        return $this->log;
    }

    public function setLog(string $log): self
    {
        $this->log = $log;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }
}
