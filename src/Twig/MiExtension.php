<?php

namespace App\Twig;

use App\Entity\Likes;
use App\Entity\Mensajes;
use App\Entity\Respuesta;
use App\Entity\Seguir;
use App\Entity\User;
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
            new TwigFunction('ComentariosTotales', [$this, 'ComentariosTotales']),
            new TwigFunction('UserLogin', [$this, 'UserLogin']),
            new TwigFunction('FollowUsuario', [$this, 'FollowUsuario']),
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

    public function FollowUsuario($userLogued,$idseguir)
    {
        $comprobar = FALSE;
        $em = $this->doctrine->getManager();
        $seguir = $em->getRepository(Seguir::class)->findBy(array('coduser' => $userLogued, 'codseguido' => $idseguir));
        if (empty($seguir)) {
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

    public function ComentariosTotales($codMensaje)
    {
        $em = $this->doctrine->getManager();
        $mensaje = $em->getRepository(Respuesta::class)->findBy(array('codmensaje'=>$codMensaje));
        $comentariosTotales = count($mensaje);
        return $comentariosTotales;
    }

    public function UserLogin($codUser)
    {
        $em = $this->doctrine->getManager();
        $user = $em->getRepository(User::class)->findBy(array('id'=>$codUser));
        return $user[0]-> getImg();
    }

   
}
