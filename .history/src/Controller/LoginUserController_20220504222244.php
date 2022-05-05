<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class LoginUserController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request): Response
    {
        $user=new User();
        $form=$this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user); //para que se guarde correctamente en la base de datos
            $entityManager->flush(); //lo mismo, es necesario
            $this->addFlash('exito','Se registró exitosamente :)');
            return $this->redirectToRoute('app_login'); //redirigimos al signUp de momento
        }
        return $this->render('login_user/index.html.twig', [
            'controller_name' => 'LoginUserController',
            'formulario'=>$form->createView()
        ]);
    }
}
