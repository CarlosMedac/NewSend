<?php

namespace App\Entity;

use App\Repository\SeguidoresRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeguidoresRepository::class)
 */
class Seguidores
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $coduser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codseguidor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoduser(): ?string
    {
        return $this->coduser;
    }

    public function setCoduser(string $coduser): self
    {
        $this->coduser = $coduser;

        return $this;
    }

    public function getCodseguidor(): ?string
    {
        return $this->codseguidor;
    }

    public function setCodseguidor(string $codseguidor): self
    {
        $this->codseguidor = $codseguidor;

        return $this;
    }
}
