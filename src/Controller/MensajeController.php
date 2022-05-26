<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Likes;
use App\Entity\Mensajes;
use App\Entity\Respuesta;
use App\Entity\Subrespuesta;
use App\Form\ComentariosType;
use App\Form\MensajeType;
use App\Form\SubRespuestaType;
use App\Repository\MensajesRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security as CoreSecurity;
use Symfony\Component\String\Slugger\SluggerInterface;

class MensajeController extends AbstractController
{
    /**
     * @Route("/mensaje/{id}", name="mensaje")
     */
    public function index($id,Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {       
            /** @var User $user */
            $user = $this->getUser();
            if(!empty($user)) {
            $userId = $user->getId();
            $username = $user->getUserIdentifier();
            }

            $em = $doctrine->getManager();
 
            $respuesta = new Respuesta();
            $form = $this->createForm(ComentariosType::class,$respuesta);
            $form->handleRequest($request);
            
            if($form->isSubmitted() && $form->isValid()) {
        
                $respuestaMensaje = $form->get('respuesta')->getData(); 
                $imagen = $form->get('imagen')->getData(); 

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
    
                    $respuesta->setImagen($newFilename);
                }

                $respuesta->setRespuesta($respuestaMensaje);
                $respuesta->setCoduser($userId);
                $respuesta->setNombreUser($username);
                $respuesta->setCodmensaje($id);
                $em->persist($respuesta);
                $em->flush();
                return $this->redirectToRoute("mensaje",['id'=>$id]);
            }

            $comentarios = $em->getRepository(Respuesta::class)->findBy(array('codmensaje'=>$id), array('fechapublicacion'=>'desc'));

            $subrespuesta = $em->getRepository(Subrespuesta::class);

            $verMensaje = $em->getRepository(Mensajes::class)->findBy(array('id'=>$id), array('fechapublicacion'=>'desc'));
            return $this->render('mensaje/index.html.twig', [
                'mensajes'      => $verMensaje,
                'user'          => $user,
                'comentarios'   => $comentarios,
                'subrespuesta'  => $subrespuesta,
                'formulario'    => $form->createView(),
                'id'            => $id,
            ]);

    }
}
