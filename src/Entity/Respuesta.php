<?php

namespace App\Entity;

use App\Repository\RespuestaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RespuestaRepository::class)
 */
class Respuesta
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
    private $codrespuesta;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codmensaje;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $respuesta;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodrespuesta(): ?string
    {
        return $this->codrespuesta;
    }

    public function setCodrespuesta(string $codrespuesta): self
    {
        $this->codrespuesta = $codrespuesta;

        return $this;
    }

    public function getCodmensaje(): ?string
    {
        return $this->codmensaje;
    }

    public function setCodmensaje(string $codmensaje): self
    {
        $this->codmensaje = $codmensaje;

        return $this;
    }

    public function getRespuesta(): ?string
    {
        return $this->respuesta;
    }

    public function setRespuesta(string $respuesta): self
    {
        $this->respuesta = $respuesta;

        return $this;
    }
}
