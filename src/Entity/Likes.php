<?php

namespace App\Entity;

use App\Repository\LikesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LikesRepository::class)
 */
class Likes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $codigoMensaje;

    /**
     * @ORM\Column(type="integer")
     */
    private $codigoUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigoMensaje(): ?int
    {
        return $this->codigoMensaje;
    }

    public function setCodigoMensaje(int $codigoMensaje): self
    {
        $this->codigoMensaje = $codigoMensaje;

        return $this;
    }

    public function getCodigoUser(): ?int
    {
        return $this->codigoUser;
    }

    public function setCodigoUser(int $codigoUser): self
    {
        $this->codigoUser = $codigoUser;

        return $this;
    }
}
