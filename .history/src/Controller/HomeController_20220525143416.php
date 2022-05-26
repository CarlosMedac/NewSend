<?php
namespace App\Controller;

use App\Entity\Likes;
use App\Entity\Mensajes;
use App\Entity\Seguir;

use App\Form\MensajeType;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function home(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if(!empty($user)){
        $userId = $user->getId();
        $username = $user->getUserIdentifier();
        }
        
        $em = $doctrine->getManager();
        $query = new Query($em);
        $follow = $em->getRepository(Seguir::class)->findBy(array('coduser'=>$userId));

        $mensaje = new Mensajes();
        $form = $this->createForm(MensajeType::class,$mensaje);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
           
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

                $mensaje->setImagen($newFilename);
            }

            $mensaje->setCoduser($userId);
            $mensaje->setNombreUser($username);
            $em->persist($mensaje);
            $em->flush();
            return $this->redirectToRoute("home");
        }

        if ($request->isXmlHttpRequest()) {
            try {
                $prepage=8;
                $numpage= $request->request->get('page');
                $posi= (($numpage-1)*$prepage);
                $consulta = 'SELECT u
                FROM App\Entity\Mensajes u ';
                $consulta = $consulta.' WHERE '; 
                if (!empty($follow)) {
                    foreach ($follow as $follow) {
                            $consulta = $consulta.' u.coduser = '.$follow->getCodseguido().' OR ';
                    }
                }
                $consulta = $consulta.' u.coduser = '.$userId.' ';
                $consulta = $consulta.' ORDER BY u.fechapublicacion DESC';
                $query->setDQL($consulta);
                $query->setFirstResult($posi);
                $query->setMaxResults($prepage);
                $verMensaje = $query->getResult();
    
                $mensajes = $this->render('home/mensaje.html.twig', array(
                                'mensajes' => $verMensaje,
                                'user' => $user,
                            ));
                            
                return $mensajes;

            } catch (Exception $e){
                return new JsonResponse(['confirmado'=>$e]);
            }
       } else {
            return $this->render('home/home.html.twig', array(
                'pagina' => 'Inicio',
                'mensajes' => " ",
                'formulario' => $form->createView(),
                'user' => $user,

            ));
        }

}

    /**
     * @Route("/Eliminar", name="Eliminar")
     */
    public function Eliminar(Request $request,  ManagerRegistry $doctrine){
        if ($request->isXmlHttpRequest()) {
            $em = $doctrine->getManager();
            $id = $request->request->get('id');
            $mensajeEliminado = $doctrine->getRepository(Mensajes::class)->find($id);
            $em->remove($mensajeEliminado);
            $em->flush();
            return new JsonResponse(['confirmado'=>"OK"]);
        } else {
            return new JsonResponse(['confirmado'=>"KO"]);
        }
    }

    /**
     * @Route("/Like", name="Like")
     */
    public function Like(Request $request,  ManagerRegistry $doctrine){
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

    /**
     * @Route("/QuitarLike", name="QuitarLike")
     */
    public function QuitarLike(Request $request,  ManagerRegistry $doctrine){
        if ($request->isXmlHttpRequest()) {
            try {
                $em = $doctrine->getManager();
                $idMensaje = $request->request->get('idMensaje');
                $idUsuario = $request->request->get('idUsuario');
                $likeEliminado = $em->getRepository(Likes::class)->findBy(array('codigoMensaje'=>$idMensaje,'codigoUser'=>$idUsuario));
                
                foreach ($likeEliminado as $like) {
                    $em->remove($like);
                    $em->flush();
                }
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
