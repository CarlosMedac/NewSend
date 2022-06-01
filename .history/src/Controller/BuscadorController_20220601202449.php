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
 
    public function index(Request $request,ManagerRegistry $doctrine): Response
    {
       /** @var User $user */
        $user = $this->getUser();
        if(!empty($user)){
            $userId = $user->getId();
            $username = $user->getUserIdentifier();
            }
        $form=$this->createForm(BarraBusquedaType::class);
        $form->handleRequest($request);
        $entityManager = $doctrine->getManager();
        $query = new Query($entityManager);
        
        $query->setDQL(
            "SELECT u
            FROM App\Entity\User u WHERE u.id <> $userId"
        );
        $query->setMaxResults(5);
        $UsersPrevia = $query->getResult();
        
        if ($request->isXmlHttpRequest()) {
            try {
                $requestUser= $request->get('userSolicitado');
                $query->setDQL(
                    "SELECT u
                    FROM App\Entity\User u 
                    WHERE u.username LIKE :usrname"
                )
                ->setParameter('usrname',"%".$requestUser."%" );
                
                $traerUsers = $query->getResult();

                $usersBD = $this->render('buscador/barraBusqueda.html.twig', array(
                                'test' => $traerUsers,
                                'usuarioLogueadoId' => $userId,
                            ));
                return ($usersBD);
            } 
            catch (Exception $e)
            {
                return new JsonResponse(['ERROR...'=>$e]);
            }
        } 
        else {
           return $this->render('buscador/index.html.twig', [
            'pagina'    => 'Buscador',
            'formulario'=> $form->createView(),
            'user'      => $user,
            'test'      => $UsersPrevia,
            'usuarioLogueadoId' => "",

        ]);
        }
    }
 

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
