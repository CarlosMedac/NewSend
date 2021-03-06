<?php

namespace App\Controller;

use App\Entity\Mensajes;
use App\Entity\Seguir;
use App\Entity\User;
use App\Form\MensajeType;
use Doctrine\ORM\EntityManagerInterface;
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

        if(!empty($user)){
            foreach ($user as $user) {
                $userId = $user->getId();
                $username = $user->getUserIdentifier();
            } 
        }

        $mensaje = new Mensajes();
        $form = $this->createForm(MensajeType::class,$mensaje);
        $form->handleRequest($request);

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
            return $this->redirectToRoute("perfil", array('id' => $id));
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
                                'mensajes'          => $verMensaje,
                                'usuarioLogueado'   => $userLoguedname,
                                'usuarioLogueadoId' => $userLoguedId,
                                'usuarioId'         => $userId,
                            ));
                            
                return $mensajes;

            } catch (Exception $e){
                return new JsonResponse(['confirmado'=>$e]);
            }
       } else {
            return $this->render('perfil/index.html.twig', array(
                'pagina'        => 'Perfil',
                'mensajes'      => " ",
                'formulario'    => $form->createView(),
                'user'          => $userLogued,
                'usuario'       => $user,
                'totalMensajes' => $totalMensajes,
                'seguidores'    => $totalSeguidores,
                'seguidos'      => $totalSeguidos,
            ));
        }

    }

    /**
     * @Route("/perfil/seguidores/{id}", name="seguidores")
     */
    public function seguidores($id,Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        /** @var User $userLogued */
        $userLogued = $this->getUser();
        if(!empty($userLogued)){
            $userLoguedId = $userLogued->getId();
            $userLoguedname = $userLogued->getUserIdentifier();
        }

        $em = $doctrine->getManager();
        $user = $em->getRepository(User::class)->findBy(array('id'=>$id));

        if(!empty($user)){
            foreach ($user as $user) {
                $userId = $user->getId();
                $username = $user->getUserIdentifier();
            } 
        }
        //Seguidores
        $seguidores = $em->getRepository(Seguir::class)->findBy(array("codseguido"=>$id));
        
        $totalSeguidores = count($seguidores);
        foreach ($seguidores as $k => $seg) {
            $userSeguidos[$k] = $em->getRepository(User::class)->findBy(array('id'=>$seg->getCoduser()));
        }
        if (empty($userSeguidos)){
            $userSeguidos=[];
        }

            return $this->render('perfil/indexSeguidos.html.twig', array(
                'pagina'    => 'Perfil',
                'mensajes'  => " ",
                'id'        => $id,
                'user'      => $userLogued,
                'usuario'   => $user,
                'seguidos'  => $userSeguidos,
            ));

    }

    /**
     * @Route("/perfil/seguidos/{id}", name="seguidos")
     */
    public function seguidos($id,Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        /** @var User $userLogued */
        $userLogued = $this->getUser();
        if(!empty($userLogued)){
            $userLoguedId = $userLogued->getId();
            $userLoguedname = $userLogued->getUserIdentifier();
        }

        $em = $doctrine->getManager();
        $user = $em->getRepository(User::class)->findBy(array('id'=>$id));

        if(!empty($user)){
            foreach ($user as $user) {
                $userId = $user->getId();
                $username = $user->getUserIdentifier();
            } 
        }
        //Seguidores
        $seguidores = $em->getRepository(Seguir::class)->findBy(array("coduser"=>$id));

        $totalSeguidores = count($seguidores);
        foreach ($seguidores as $k => $seg) {
            $userSeguidos[$k] = $em->getRepository(User::class)->findBy(array('id'=>$seg->getCodseguido()));
        }
        if (empty($userSeguidos)){
            $userSeguidos=[];
        }
            return $this->render('perfil/indexSeguidos.html.twig', array(
                'pagina'    => 'Perfil',
                'mensajes'  => " ",
                'id'        => $id,
                'user'      => $userLogued,
                'usuario'   => $user,
                'seguidos'  => $userSeguidos,
            ));

    }


    /**
     * @Route("/follow", name="follow")
     */
    public function follow(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager){
        if ($request->isXmlHttpRequest()) {
            try{
                    $userLogued = $request->request->get('userLogued');
                    $idseguir = $request->request->get('idseguir');
                    $em = $doctrine->getManager();
                    $seguir = new Seguir();
                    $seguir->setCoduser($userLogued);
                    $seguir->setCodseguido($idseguir);
                    $em->persist($seguir);
                    $em->flush();  
                    return new JsonResponse(['confirmado'=>'OK']);
            } catch (Exception $e){
                return new JsonResponse(['confirmado'=>'No se ha hecho']);
            }   
        } else {
            return new JsonResponse(['confirmado'=>"KO"]);
        }
    }

    /**
     * @Route("/unfollow", name="unfollow")
     */
    public function unfollow(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $entityManager){
        if ($request->isXmlHttpRequest()) {
            try{
                    $userLogued = $request->request->get('userLogued');
                    $idseguir = $request->request->get('idseguir');
                    $em = $doctrine->getManager();
                    $seguir = $doctrine->getRepository(Seguir::class)->findBy(array('coduser' => $userLogued, 'codseguido' => $idseguir));
                    foreach ($seguir as $seguir) {};
                    $em->remove($seguir);
                    $em->flush();
                    return new JsonResponse(['confirmado'=>'Eliminado']);
            } catch (Exception $e){
                return new JsonResponse(['confirmado'=>$idseguir]);
            }   
        } else {
            return new JsonResponse(['confirmado'=>"KO"]);
        }
    }
}