<?php

namespace App\Entity;

use App\Repository\SubrespuestaRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubrespuestaRepository::class)
 */
class Subrespuesta
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
    private $codmensaje;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $respuestasub;
     /**
     * @ORM\Column(type="string", length=255)
     */
    private $coduser;
     /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombreuser;
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

    public function getCodmensaje(): ?string
    {
        return $this->codmensaje;
    }

    public function setCodmensaje(string $codmensaje): self
    {
        $this->codmensaje = $codmensaje;

        return $this;
    }

    public function getRespuestaSub(): ?string
    {
        return $this->respuestasub;
    }

    public function setRespuestaSub(string $respuestasub): self
    {
        $this->respuestasub = $respuestasub;

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
    public function getNombreUser(): ?string
    {
        return $this->nombreuser;
    }

    public function setNombreUser(string $nombreuser): self
    {
        $this->nombreuser = $nombreuser;

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
