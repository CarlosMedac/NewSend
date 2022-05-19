<?php

namespace App\Controller;


use App\Entity\Likes;
use App\Entity\Mensajes;
use App\Entity\Seguir;
use App\Entity\User;
use App\Form\EditarType;
use App\Form\MensajeType;
use App\Repository\MensajesRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security as CoreSecurity;
use Symfony\Component\String\Slugger\SluggerInterface;

class EditarPerfilController extends AbstractController
{
    #[Route('/perfil/editar/{id}', name: 'editar')]
    public function editarPerfil($id,Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
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

        $userForm = new User();
        $form = $this->createForm(EditarType::class,$userForm);
        
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
           
            $imagen = $form->get('img')->getData(); 

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

                $userForm->setImg($newFilename);
            }

            $userForm->setUsername($username);
            $em->persist($userForm);
            $em->flush();
            return $this->redirectToRoute("perfil");
        }
         return $this->render('editar_perfil/index.html.twig', array(
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
