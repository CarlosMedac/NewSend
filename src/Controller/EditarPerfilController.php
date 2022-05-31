<?php

namespace App\Controller;


use App\Entity\Likes;
use App\Entity\User;
use App\Form\EditarType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class EditarPerfilController extends AbstractController
{
    #[Route('/perfil/editar/{id}', name: 'editar')]
    public function editarPerfil($id,Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        /** @var User $userLogued */
        $userLogued = $this->getUser();
        if(!empty($userLogued)){
            $userLoguedname = $userLogued->getUserIdentifier();
            $biografia      = $userLogued->getDescription();
            $email          = $userLogued->getEmail();
        }

        $em = $doctrine->getManager();

        $userForm = new User();
        $form = $this->createForm(EditarType::class,$userForm);
        $form->add('username', TypeTextType::class, array(
            'data' => $userLoguedname, 'label' => false,
        ));
        $form->add('description', TextareaType::class, array(
            'data' => $biografia, 'label' => false,
        ));
        $form->add('email', TypeTextType::class, array(
            'data' => $email, 'label' => false,
        ));

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $nombre      = $form->get('username')->getData();
            $descripcion = $form->get('description')->getData();
            $email       = $form->get('email')->getData();
            $imagen      = $form->get('img')->getData(); 

            if ($imagen) {

                $originalFilename = pathinfo($imagen->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imagen->guessExtension();

                try {
                    $imagen->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new Exception("No se ha podido cargar la imagen".$e);
                }

                $userLogued->setImg($newFilename);
            }

            $userLogued->setUsername($nombre);
            $userLogued->setDescription($descripcion);
            $userLogued->setEmail($email);

            $em->flush();
            return $this->redirectToRoute("perfil", array('id' => $id));
        }
         return $this->render('editar_perfil/index.html.twig', array(
             'pagina'      => 'Editar',
             'formulario'  => $form->createView(),
             'user'        => $userLogued,
         ));
    }

    /**
     * @Route("/checkPass", name="checkPass")
     */
    public function checkPass(Request $request, UserInterface $user, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder){
        if ($request->isXmlHttpRequest()) {
            $pass= [];
            $pass['password'] = $request->request->get('pass');
            $seguridad = new LoginFormAuthenticator($entityManager,$urlGenerator, $csrfTokenManager, $passwordEncoder);
            try {
                $valid=$seguridad->checkCredentials($pass,$user);
                if ($valid) {
                    return new JsonResponse(['confirmado'=>$valid]);
                } else {
                    return new JsonResponse(['confirmado'=>$valid]);
                }  
            } catch (Exception $e){
                return new JsonResponse(['confirmado'=>$e]);
            }   
        } else {
            return new JsonResponse(['confirmado'=>"KO"]);
        }
    }

    /**
     * @Route("/changePass", name="changePass")
     */
    public function changePass(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder){
        if ($request->isXmlHttpRequest()) {
            try{
                    $pass = $request->request->get('pass');
                    $id = $request->request->get('id');
                    $em = $doctrine->getManager();
                    
                    $user = $em->getRepository(User::class)->findBy(array('id'=>$id));
                    foreach ($user as $user) {
                        //Para Hacer que no sea un array
                    }
                    $user->setPassword($passwordEncoder->encodePassword($user,$pass));
                    $em->persist($user);
                    $em->flush();  
                    return new JsonResponse(['confirmado'=>'OK']);
            } catch (Exception $e){
                return new JsonResponse(['confirmado'=>'No se ha hecho']);
            }   
        } else {
            return new JsonResponse(['confirmado'=>"KO"]);
        }
    }

}
