<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class RegistroUserController extends AbstractController
{
    /**
     * @Route("/registrarse", name="registrarse")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user=new User();
        $form=$this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setPassword($passwordEncoder->encodePassword($user,$form['password']->getData()));
            $entityManager->persist($user);
            $entityManager->flush();
            // $this->addFlash('exito', User::REGISTRO_CORRECTO);     
            return $this->redirectToRoute('home');
        }
        return $this->render('registro_user/index.html.twig', [
            'controller_name'   => 'RegistroUserController',
            'formulario'        => $form->createView()
        ]);
    }
}
