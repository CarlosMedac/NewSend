<?php

namespace App\Controller;


use App\Entity\Likes;
use App\Entity\LikesRespuesta;
use App\Entity\Mensajes;
use App\Entity\Respuesta;
use App\Entity\Subrespuesta;
use App\Form\ComentariosType;
use Symfony\Component\HttpFoundation\Response;
use App\Form\MensajeType;
use App\Form\SubRespuestaType;
use App\Repository\MensajesRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security as CoreSecurity;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ComentariosController extends AbstractController
{
    /**
     * @Route("/comentario/{id}", name="comentario")
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

            $subRespuesta = new Subrespuesta();
            $formRespuesta = $this->createForm(SubRespuestaType::class,$subRespuesta);
            $formRespuesta->handleRequest($request);
            
            if($formRespuesta->isSubmitted() && $formRespuesta->isValid()) {
        
                $respuestaMensaje = $formRespuesta->get('respuestasub')->getData(); 

                $subRespuesta->setRespuestaSub($respuestaMensaje);
                $subRespuesta->setCoduser($userId);
                $subRespuesta->setNombreUser($username);
                $subRespuesta->setCodmensaje($id);
                $em->persist($subRespuesta);
                $em->flush();
                return $this->redirectToRoute("comentario",['id'=>$id]);
            }

            $comentarios = $em->getRepository(Subrespuesta::class)->findBy(array('codmensaje'=>$id), array('fechapublicacion'=>'desc'));
            $verMensaje = $em->getRepository(Respuesta::class)->findBy(array('id'=>$id), array('fechapublicacion'=>'desc'));

            return $this->render('comentarios/index.html.twig', [
                'comentarios'   => $comentarios,
                'user'          => $user,
                'mensajes'      => $verMensaje,
                'formRespuesta' => $formRespuesta->createView()
            ]);

    }

    /**
     * @Route("/LikeRespuesta", name="LikeRespuesta")
     */
    public function LikeRespuesta(Request $request,  ManagerRegistry $doctrine){
        if ($request->isXmlHttpRequest()) {
            try {
                $Likes = new LikesRespuesta();
                $em = $doctrine->getManager();
                $idMensaje = $request->request->get('idMensaje');
                $idUsuario = $request->request->get('idUsuario');
                $Likes->setCodigoMensaje($idMensaje);
                $Likes->setCodigoUser($idUsuario);
                $em->persist($Likes);
                $em->flush();
                $mensaje = $em->getRepository(LikesRespuesta::class)->findBy(array('codigoMensaje'=>$idMensaje));
                $liketotal = count($mensaje);
                return new JsonResponse(['confirmado'=>'OK','mensaje'=>$idMensaje,'usuario'=>$idUsuario,'total'=>$liketotal]);
            } catch (Exception $e){
                return new JsonResponse(['confirmado'=>$e]);
            }
            
        } else {
            return new JsonResponse(['confirmado'=>"KO"]);
        }
    }

    /**
     * @Route("/QuitarLikeRespuesta", name="QuitarLikeRespuesta")
     */
    public function QuitarLikeRespuesta(Request $request,  ManagerRegistry $doctrine){
        if ($request->isXmlHttpRequest()) {
            try {
                $em = $doctrine->getManager();
                $idMensaje = $request->request->get('idMensaje');
                $idUsuario = $request->request->get('idUsuario');
                $likeEliminado = $em->getRepository(LikesRespuesta::class)->findBy(array('codigoMensaje'=>$idMensaje,'codigoUser'=>$idUsuario));
                
                foreach ($likeEliminado as $like) {
                    $em->remove($like);
                    $em->flush();
                }
                $mensaje = $em->getRepository(LikesRespuesta::class)->findBy(array('codigoMensaje'=>$idMensaje));
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
