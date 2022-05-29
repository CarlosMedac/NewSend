<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoticiasController extends AbstractController
{
    /**
     * @Route("/noticias", name="noticias")
     */
    public function index(): Response
    {
        return $this->render('noticias/index.html.twig', [
            'controller_name' => 'NoticiasController',
        ]);
    }
}
