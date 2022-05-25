<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\BarraBusquedaType;

use Doctrine\ORM\Query;
class BuscadorController extends AbstractController
{
    /**
     * @Route("/buscador", name="buscador")
     */
 
    public function index(Request $request): Response
    {
         
        
        return $this->render('buscador/index.html.twig', [
            'userEncontrado' => $userEncontrado,
            'formulario'=>$form->createView(),
            'test' => "",
        ]);
        

       
        
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
     * @Route("/buscadorUsers", name="buscadorUsers")
     */
    public function Buscador(Request $request,  ManagerRegistry $doctrine){
        if ($request->isXmlHttpRequest()) {
            try {
                             
                $entityManager = $this->getDoctrine()->getManager();
                $query = new Query($entityManager);
                $requestUser= $request->get('userSolicitado');
                $query->setDQL(
                    "SELECT u.username
                    FROM App\Entity\User u 
                    WHERE u.username LIKE :usrname"
                )
                ->setParameter('usrname',"%".$requestUser."%" );
                
                $traerUsers = $query->getResult();

            //   $this->render('buscador/index.html.twig', array(
            //         'test' => $traerUsers,
                     
            //     ));
            //     return new JsonResponse(['usersEncontrados'=>$traerUsers]);

                $mensajes = $this->render('buscador/index.html.twig', array(
                    'test' => $traerUsers,
          
                ));
                
                return ($mensajes);
            } 
            catch (Exception $e)
            {
                return new JsonResponse(['ERROR'=>$e]);
            }
        } 
        else {
            return new JsonResponse(['ELSE'=>"Terrible"]);
        }
    }

}
