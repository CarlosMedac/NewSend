<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\LoginManualType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class SignInUserManualController extends AbstractController
{
    /**
     * @Route("/SignIn2", name="SignIn2")
     */
    public function index(Request $request)
    {
        $user=new User();
        $form=$this->createForm(LoginManualType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
           

            $entityManager->persist($user); //para que se guarde correctamente en la base de datos
            $entityManager->flush(); //lo mismo, es necesario
            $this->addFlash('exito','Se registró exitosamente :)');     
            //return $this->redirectToRoute('app_login'); //redirigimos al signUp de momento
        }
        return $this->render('login_user/loginManual.html.twig', [
            'controller_name' => 'SignInUserManualController',
            'formulario'=>$form->createView()
        ]);
    }
}
