<?php

namespace App\Entity;

use App\Repository\MensajesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MensajesRepository")
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mensaje;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechapublicacion;

    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $imagen;

    public function __construct()
    {
        $this->fechapublicacion= new \DateTime();
    }

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

    public function getFechaPublicacion(): ?\DateTimeInterface
    {
        return $this->fechapublicacion;
    }

    public function setFechaPublicacion(\DateTimeInterface $fechapublicacion): self
    {
        $this->fechapublicacion = $fechapublicacion;

        return $this;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function setImagen(String $imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }
}
