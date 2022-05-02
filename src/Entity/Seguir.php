<?php

namespace App\Entity;

use App\Repository\SeguirRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeguirRepository::class)
 */
class Seguir
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
    private $codseguido;

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

    public function getCodseguido(): ?string
    {
        return $this->codseguido;
    }

    public function setCodseguido(string $codseguido): self
    {
        $this->codseguido = $codseguido;

        return $this;
    }
}
