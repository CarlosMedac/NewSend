<?php

namespace App\Twig;

use App\Entity\Likes;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormRegistryInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MiExtension extends AbstractExtension
{
    protected $doctrine;
    // Recuperar doctrine desde el constructor
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('Like', [$this, 'Like']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('LikeUsuario', [$this, 'LikeUsuario']),
            new TwigFunction('LikesTotales', [$this, 'LikesTotales']),
        ];
    }

    public function LikeUsuario($codUsuario,$codMensaje)
    {
        $comprobar = FALSE;
        $em = $this->doctrine->getManager();
        $mensaje = $em->getRepository(Likes::class)->findBy(array('codigoMensaje'=>$codMensaje));
        $mensajeUsuario=[];
        foreach ($mensaje as $k => $mensaje) {
            $mensajeUsuario[$k]=$mensaje->getCodigoUser();
        }
        if (in_array(strval($codUsuario),$mensajeUsuario)) {
            $comprobar = TRUE;
        }
        return $comprobar;
    }

    public function LikesTotales($codMensaje)
    {
        $em = $this->doctrine->getManager();
        $mensaje = $em->getRepository(Likes::class)->findBy(array('codigoMensaje'=>$codMensaje));
        $likesTotales = count($mensaje);
        return $likesTotales;
    }
}
