<?php

namespace App\Controller;

use App\Entity\Mensajes;
use App\Entity\Seguir;
use App\Entity\User;
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

class PerfilController extends AbstractController
{
    /**
     * @Route("/perfil/{id}", name="perfil")
     */
    public function perfil($id,Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        /** @var User $userLogued */
        $userLogued = $this->getUser();
        if(!empty($userLogued)){
            $userLoguedId = $userLogued->getId();
            $userLoguedname = $userLogued->getUserIdentifier();
        }

        $em = $doctrine->getManager();
        $user = $em->getRepository(User::class)->findBy(array('id'=>$id));
        // var_dump($user);
        // exit;
        if(!empty($user)){
            foreach ($user as $user) {
                $userId = $user->getId();
                $username = $user->getUserIdentifier();
            } 
        }

        $mensaje = new Mensajes();
        $form = $this->createForm(MensajeType::class,$mensaje);
        
        $query = new Query($em);
        //Mensajes
        $verMensaje = $em->getRepository(Mensajes::class)->findBy(array("coduser"=>$userId), array('fechapublicacion'=>'desc'));
        $totalMensajes = count($verMensaje);
        
        //Seguidores
        $seguidores = $em->getRepository(Seguir::class)->findBy(array("codseguido"=>$userId));
        $totalSeguidores = count($seguidores);
        
        //Seguidos
        $follow = $em->getRepository(Seguir::class)->findBy(array('coduser'=>$userId));
        $totalSeguidos = count($follow);

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
            return $this->redirectToRoute("perfil");
        }

        if ($request->isXmlHttpRequest()) {
            try {
                $prepage=8;
                $numpage= $request->request->get('page');
                $posi= (($numpage-1)*$prepage);
                $consulta = 'SELECT u
                FROM App\Entity\Mensajes u WHERE';
                $consulta = $consulta.' u.coduser = '.$userId.' ';
                $consulta = $consulta.' ORDER BY u.fechapublicacion DESC';
                $query->setDQL($consulta);
                $query->setFirstResult($posi);
                $query->setMaxResults($prepage);
                $verMensaje = $query->getResult();
    
                $mensajes = $this->render('perfil/mensaje.html.twig', array(
                                'mensajes' => $verMensaje,
                                'usuarioLogueado' => $userLoguedname,
                                'usuarioLogueadoId' => $userLoguedId,
                                'usuarioId' => $userId,
                            ));
                            
                return $mensajes;

            } catch (Exception $e){
                return new JsonResponse(['confirmado'=>$e]);
            }
       } else {
            return $this->render('perfil/index.html.twig', array(
                'pagina' => 'Perfil',
                'mensajes' => " ",
                'formulario' => $form->createView(),
                'user' => $user,
                'usuarioLogueadoId' => $userLoguedId,
                'usuarioId' => $userId,
                'totalMensajes' =>$totalMensajes,
                'seguidores' =>$totalSeguidores,
                'seguidos' =>$totalSeguidos,
            ));
        }

    }
}