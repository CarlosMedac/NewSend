<?php
namespace App\Controller;

use App\Entity\Mensajes;
use App\Form\MensajeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function home(Request $request, ManagerRegistry $doctrine): Response
    {
        $mensaje = new Mensajes();
        $form = $this->createForm(MensajeType::class,$mensaje);
        $em = $doctrine->getManager();
        $verMensaje = $em->getRepository(Mensajes::class)->findAll();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $mensaje->setCoduser(1);
            $mensaje->setFecha(date('d-m-Y'));
            $mensaje->setHora(1);
            $em->persist($mensaje);
            $em->flush();
        }
        return $this->render('home/home.html.twig', array(
            'pagina' => 'Inicio',
            'mensajes' => $verMensaje,
            'formulario' => $form->createView()
        ));
    }
}
