<?php

namespace App\Entity;

use App\Repository\MensajesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MensajesRepository::class)
 */
class Mensajes
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
    private $mensaje;

    /**
     * @ORM\Column(type="string")
     */
    private $fecha;

    /**
     * @ORM\Column(type="integer")
     */
    private $hora;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setCodmensaje(string $codmensaje): self
    {
        $this->codmensaje = $codmensaje;

        return $this;
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

    public function getMensaje(): ?string
    {
        return $this->mensaje;
    }

    public function setMensaje(string $mensaje): self
    {
        $this->mensaje = $mensaje;

        return $this;
    }

    public function getFecha(): ?string
    {
        return $this->fecha;
    }

    public function setFecha(string $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getHora(): ?int
    {
        return $this->hora;
    }

    public function setHora(int $hora): self
    {
        $this->hora = $hora;

        return $this;
    }
}
