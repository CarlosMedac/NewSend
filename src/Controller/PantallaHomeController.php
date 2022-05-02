<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
class PantallaHomeController extends AbstractController
{
    /**
     * @Route("/pantallaHome", name="pantallaHome")
     */
    public function index(Request $request): Response
    {
        
        return $this->render('pantalla_home/index.html.twig', [
            'controller_name' => 'Hola soy PantallaHomeController',
        ]);
    }
}
