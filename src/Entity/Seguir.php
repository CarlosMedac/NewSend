<?php

namespace App\Entity;

use App\Repository\SeguirRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SeguirRepository::class)
 */
class Seguir
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer",)
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $coduser;

    /**
     * @ORM\Column(type="integer")
     */
    private $codseguido;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCoduser(): ?int
    {
        return $this->coduser;
    }

    public function setCoduser(int $coduser): self
    {
        $this->coduser = $coduser;

        return $this;
    }

    public function getCodseguido(): ?int
    {
        return $this->codseguido;
    }

    public function setCodseguido(int $codseguido): self
    {
        $this->codseguido = $codseguido;

        return $this;
    }
}
