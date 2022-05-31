<?php

namespace App\Twig;

use App\Entity\Likes;
use App\Entity\LikesRespuesta;
use App\Entity\Mensajes;
use App\Entity\Respuesta;
use App\Entity\Seguir;
use App\Entity\Subrespuesta;
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
            new TwigFunction('LikeUsuarioRespuesta', [$this, 'LikeUsuarioRespuesta']),
            new TwigFunction('ComentariosTotalesRespuesta', [$this, 'ComentariosTotalesRespuesta']),
            new TwigFunction('ComentariosRespuesta', [$this, 'ComentariosRespuesta']),
            new TwigFunction('LikesTotalesRespuesta', [$this, 'LikesTotalesRespuesta']),
            new TwigFunction('UserLogin', [$this, 'UserLogin']),
            new TwigFunction('FollowUsuario', [$this, 'FollowUsuario']),
            new TwigFunction('timeago', [$this, 'timeago']),
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

    public function LikeUsuarioRespuesta($codUsuario,$codMensaje)
    {
        $comprobar = FALSE;
        $em = $this->doctrine->getManager();
        $mensaje = $em->getRepository(LikesRespuesta::class)->findBy(array('codigoMensaje'=>$codMensaje));
        $mensajeUsuario=[];
        foreach ($mensaje as $k => $mensaje) {
            $mensajeUsuario[$k]=$mensaje->getCodigoUser();
        }
        if (in_array(strval($codUsuario),$mensajeUsuario)) {
            $comprobar = TRUE;
        }
        return $comprobar;
    }

    public function LikesTotalesRespuesta($codMensaje)
    {
        $em = $this->doctrine->getManager();
        $mensaje = $em->getRepository(LikesRespuesta::class)->findBy(array('codigoMensaje'=>$codMensaje));
        $likesTotales = count($mensaje);
        return $likesTotales;
    }

    public function ComentariosTotalesRespuesta($codMensaje)
    {
        $em = $this->doctrine->getManager();
        $mensaje = $em->getRepository(Subrespuesta::class)->findBy(array('codmensaje'=>$codMensaje));
        $comentariosTotales = count($mensaje);
        return $comentariosTotales;
    }

    public function ComentariosRespuesta($codMensaje)
    {
        $em = $this->doctrine->getManager();
        $mensaje = $em->getRepository(Subrespuesta::class)->findBy(array('codmensaje'=>$codMensaje));
        return $mensaje;
    }

    public function UserLogin($codUser)
    {
        $em = $this->doctrine->getManager();
        $user = $em->getRepository(User::class)->findBy(array('id'=>$codUser));
        return $user[0]-> getImg();
    }

    function timeago($date) {
        $time_ago        = strtotime($date);
        $current_time    = time();
        $time_difference = $current_time - $time_ago;
        $seconds         = $time_difference;
    
        $minutes = round($seconds / 60); // value 60 is seconds
        $hours   = round($seconds / 3600); //value 3600 is 60 minutes * 60 sec
        $days    = round($seconds / 86400); //86400 = 24 * 60 * 60;
        $weeks   = round($seconds / 604800); // 7*24*60*60;
        $months  = round($seconds / 2629440); //((365+365+365+365+366)/5/12)*24*60*60
        $years   = round($seconds / 31553280); //(365+365+365+365+366)/5 * 24 * 60 * 60
    
        if ($seconds <= 60){
    
          return $seconds."s";
    
        } else if ($minutes <= 60){
    
          if ($minutes == 1){
    
            return "1min";
    
          } else {
    
            return $minutes."min";
    
          }
    
        } else if ($hours <= 24){
    
          if ($hours == 1){
    
            return "1h";
    
          } else {
    
            return $hours."h";
    
          }
    
        } else if ($days <= 7){
    
          if ($days == 1){
    
            return "ayer";
    
          } else {
    
            return $days." d";
    
          }
    
        } else if ($weeks <= 4.3){
    
          if ($weeks == 1){
    
            return "hace 1 semana";
    
          } else {
    
            return "hace $weeks semanas";
    
          }
    
        } else if ($months <= 12){
    
          if ($months == 1){
    
            return "1 m";
    
          } else {
    
            return $months."m";
    
          }
    
        } else {
    
          if ($years == 1){
    
            return "1 año";
    
          } else {
    
            return $years." año";
    
          }
        }
    }
   
}
