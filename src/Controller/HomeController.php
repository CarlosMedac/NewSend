<?php
namespace App\Controller;

use App\Entity\Mensajes;
 
use App\Form\MensajeType;
use App\Repository\MensajesRepository;
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
        $username = $this->getUser()->getUserIdentifier();//esta tmb la añadí
        $user = $this->getUser();//esta tmb la añadí
        /** @var User $user */
        $user = $this->security->getUser();
        if(!empty($user)){
        $userId = $user->getId();
        }
        // $mensaje ->setCoduser($user);//esta da error
        var_dump($userId);
        // echo $username;
        exit;
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

            $mensaje->setCoduser(1);
            $em->persist($mensaje);
            $em->flush();
            return $this->redirectToRoute("home");
        }

        if ($request->isXmlHttpRequest()) {

            $prepage=4;
            $numpage= $request->request->get('page');
            $posi= (($numpage-1)*$prepage);
            $query->setDQL(
                            'SELECT u
                            FROM App\Entity\Mensajes u
                            ORDER BY u.fechapublicacion DESC'
                        );
            $query->setFirstResult($posi);
            $query->setMaxResults($prepage);
            $verMensaje = $query->getResult();
            $mensajes = $this->render('home/mensaje.html.twig', array(
                            'mensajes' => $verMensaje,
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
}
