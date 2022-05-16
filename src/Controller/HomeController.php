<?php
namespace App\Controller;

use App\Entity\Likes;
use App\Entity\Mensajes;
use App\Entity\Seguir;

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

use function PHPUnit\Framework\throwException;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function home(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $mensaje = new Mensajes();
        $form = $this->createForm(MensajeType::class,$mensaje);
        $em = $doctrine->getManager();
        $query = new Query($em);
        $verMensaje = $em->getRepository(Mensajes::class)->findBy(array(), array('fechapublicacion'=>'desc'));
        $form->handleRequest($request);
        /** @var User $user */
        $user = $this->getUser();
        if(!empty($user)){
        $userId = $user->getId();
        $username = $user->getUserIdentifier();
        }
        // echo $userId;
        $follow = $em->getRepository(Seguir::class)->findBy(array('coduser'=>$userId));
        // $follow["codseguido"];
        // $mensaje ->setCoduser($user);//esta da error
        // var_dump($follow=>codseguido);
        // exit;
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
            
            $prepage=8;
            $numpage= $request->request->get('page');
            $posi= (($numpage-1)*$prepage);
            $consulta = 'SELECT u
            FROM App\Entity\Mensajes u ';
            if (!empty($follow)) {
                $consulta = $consulta.' WHERE '; 
                foreach ($follow as $k => $follow) {
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
                            'usuarioLogueado' => $userId
                        ));
                        
            return $mensajes;

       } else {
            return $this->render('home/home.html.twig', array(
                'pagina' => 'Inicio',
                'mensajes' => " ",
                'formulario' => $form->createView()
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
            $Likes = new Likes();
            $em = $doctrine->getManager();
            $idMensaje = $request->request->get('idMensaje');
            $idUsuario = $request->request->get('idUsuario');
            $Likes->setCodigoMensaje($idMensaje);
            $Likes->setCodigoUser($idUsuario);
            $em->persist($Likes);
            $em->flush();
            return new JsonResponse(['confirmado'=>"OK"]);
        } else {
            return new JsonResponse(['confirmado'=>"KO"]);
        }
    }

    /**
     * @Route("/QuitarLike", name="QuitarLike")
     */
    public function QuitarLike(Request $request,  ManagerRegistry $doctrine){
        if ($request->isXmlHttpRequest()) {
            // $Likes = new Likes();
            // $em = $doctrine->getManager();
            // $idMensaje = $request->request->get('idMensaje');
            // $idUsuario = $request->request->get('idUsuario');
            // $Likes->setCodigoMensaje($idMensaje);
            // $Likes->setCodigoUser($idUsuario);
            // $em->persist($Likes);
            // $em->flush();
            return new JsonResponse(['confirmado'=>"OK"]);
        } else {
            return new JsonResponse(['confirmado'=>"KO"]);
        }
    }
}
