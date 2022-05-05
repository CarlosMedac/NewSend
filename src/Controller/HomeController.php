<?php
namespace App\Controller;

use App\Entity\Mensajes;
use App\Form\MensajeType;
use App\Repository\MensajesRepository;
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
        $verMensaje = $em->getRepository(Mensajes::class)->findBy(array(), array('fechapublicacion'=>'desc'));
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $imagen = $form->get('imagen')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imagen) {
                $originalFilename = pathinfo($imagen->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imagen->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imagen->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new Exception("No se ha podido cargar la imagen".$e);
                }

                // updates the 'imagenname' property to store the PDF file name
                // instead of its contents
                $mensaje->setImagen($newFilename);
            }

            $mensaje->setCoduser(1);
            $em->persist($mensaje);
            $em->flush();
            return $this->redirectToRoute("home");
        }
        return $this->render('home/home.html.twig', array(
            'pagina' => 'Inicio',
            'mensajes' => $verMensaje,
            'formulario' => $form->createView()
        ));
    }

    /**
     * @Route("/Eliminar", name="Eliminar")
     */
    public function Eliminar(Request $request,  ManagerRegistry $doctrine){
        if($request->isXmlHttpRequest()){
            $em = $doctrine->getManager();
            $id = $request->request->get('id');
            $mensajeEliminado = $doctrine->getRepository(Mensajes::class)->find($id);
            $em->remove($mensajeEliminado);
            $em->flush();
            return new JsonResponse(['confirmado'=>"OK"]);
        }else{
            return new JsonResponse(['confirmado'=>"KO"]);
        }
    }
}
