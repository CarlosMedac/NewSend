<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NoticiasController extends AbstractController
{
    /**
     * @Route("/noticias", name="noticias")
     */
    public function index(Request $request,ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        if(!empty($user)){
            $userId = $user->getId();
            $username = $user->getUserIdentifier();
            }
        include_once("simple_html_dom.php");

        $context = stream_context_create(array('http' =>  array('header' => 'Accept: application/xml')));
        $url = "http://www.europapress.es/rss/rss.aspx";
        $xmlstring = file_get_contents($url, false, $context);
        $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        $titulos="";
        for ($i=0; $i < 9; $i++) { 
            $titulos = $titulos."\n\n".$array['channel']['item'][$i]['title']."<a href='".$array['channel']['item'][$i]['link']."'> +info</a>";
        }
        return $this->render('noticias/index.html.twig', [
            'noticias' => $titulos,
            'pagina' =>'Noticias',
            'user' => $user,

        ]);
    }
}
