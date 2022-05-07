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

class ImagenController extends AbstractController
{
    /**
     * @Route("/imagen", name="imagen")
     */
    public function home(Request $request, ManagerRegistry $doctrine, SluggerInterface $slugger) : void
    {
        
        $mensaje = new Mensajes();
        $form = $this->createForm(MensajeType::class,$mensaje);
        $em = $doctrine->getManager();
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

            $mensaje->setCoduser(1);
            $em->persist($mensaje);
            $em->flush();
        }

    }
}