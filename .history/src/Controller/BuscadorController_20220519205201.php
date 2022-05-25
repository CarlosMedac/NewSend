<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\BarraBusquedaType;

use Doctrine\ORM\Query;
class BuscadorController extends AbstractController
{
    /**
     * @Route("/buscador", name="app_buscador")
     */
 
    public function index(Request $request): Response
    {
        $userEncontrado="";
        $user=new User();
        $form=$this->createForm(BarraBusquedaType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $query = new Query($entityManager);

         
            $userEncontrado="a";

            $requestUser= $request->get('username');
            $query->setDQL(
                "SELECT u.username
                FROM App\Entity\User u 
                WHERE u.username LIKE :usrname"
            )
            ->setParameter('usrname',"%".$userEncontrado."%" );

            
            $verUsers = $query->getResult();

            $users = $this->render('buscador/index.html.twig', array(
                'test' => $verUsers,
                'formulario'=>$form->createView()
            ));
            
            return $users;
        }
        else{
             return $this->render('buscador/index.html.twig', [
            'userEncontrado' => $userEncontrado,
            'formulario'=>$form->createView(),
            'test' => "",
        ]);
        }

       
        
    }
    // public function buscador(ManagerRegistry $doctrine, $username){
         
    //     $em = $doctrine->getManager();
    //     $buscarUsuarios = $em->getRepository(User::class);
    //     $userEncontrado= $buscarUsuarios->find($username);
    //     $form=$this->createFormBuilder( null)
    //     ->add('query', TextType::class)
    //     ->add('buscar',SubmitType::class)
    //     ->getForm();
    //     return $this->render('buscador/barraBusqueda.html.twig',[
    //         'form'=>$form->createView()
    //     ]);
    // }

/**
     * @Route("/buscador", name="buscador")
     */
    public function Buscador(Request $request,  ManagerRegistry $doctrine){
        if ($request->isXmlHttpRequest()) {
            try {
                $Likes = new Likes();
                $em = $doctrine->getManager();
                $idMensaje = $request->request->get('idMensaje');
                $idUsuario = $request->request->get('idUsuario');
                $Likes->setCodigoMensaje($idMensaje);
                $Likes->setCodigoUser($idUsuario);
                $em->persist($Likes);
                $em->flush();
                $mensaje = $em->getRepository(Likes::class)->findBy(array('codigoMensaje'=>$idMensaje));
                $liketotal = count($mensaje);
                return new JsonResponse(['confirmado'=>'OK','mensaje'=>$idMensaje,'usuario'=>$idUsuario,'total'=>$liketotal]);
            } catch (Exception $e){
                return new JsonResponse(['confirmado'=>$e]);
            }
            
        } else {
            return new JsonResponse(['confirmado'=>"KO"]);
        }
    }

}
