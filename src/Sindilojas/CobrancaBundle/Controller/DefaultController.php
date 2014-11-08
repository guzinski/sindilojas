<?php

namespace Sindilojas\CobrancaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $em = $this->getDoctrine()->getManager();
        
        $clientex = $em->getRepository("SindilojasCobrancaBundle:Divida")->findAll();
        
        return $this->render('SindilojasCobrancaBundle:Default:index.html.twig', array('name' => $name));
    }
}
