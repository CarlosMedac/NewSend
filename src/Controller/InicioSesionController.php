<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\LoginManualType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;


class InicioSesionController extends AbstractController
{
    /**
     * @Route("/inicioSesion", name= "inicioSesion")
     */

    
    public function index(ManagerRegistry $doctrine, Request $request,RequestStack $requestStack): Response
    {
        
        $UserLogued = new User();
        $form = $this->createForm(LoginManualType::class, $UserLogued);
        $form->handleRequest($request);

        $username = $form->get('username')->getData();
        $password = $form->get('password')->getData();
                
        //session_destroy();

        
        $em= $doctrine->getManager();
        $consulta= $em->getRepository(User::class)->findAll();
        if($form->isSubmitted() && $form->isValid())
        {
             
            foreach ($consulta as $valor) {
                if($valor->getUsername()==$username && $valor->getPassword()==$password)
                {   
                    // $session = new Session();
                    // $session->start();
                    // $session->set('userName', $username);
                   
                    return $this->redirectToRoute('home'); //redirigimos al signUp de momento

                }
            }
             //redirigimos al signUp de momento
        }
        
           
        

        return $this->render('inicio_sesion/index.html.twig', [
            'controller_name' => 'InicioSesionController',
            'form' => $form->createView(),
            'consulta'=>$consulta,
        ]);
    }
}
