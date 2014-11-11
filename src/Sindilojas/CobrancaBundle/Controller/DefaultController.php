<?php

namespace Sindilojas\CobrancaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $em = $this->getDoctrine()->getManager();
        
        $clientes = $em->getRepository("SindilojasCobrancaBundle:Cliente")->findAll();
        $lojas = $em->getRepository("SindilojasCobrancaBundle:Loja")->findAll();
        $dividas = $em->getRepository("SindilojasCobrancaBundle:Divida")->findAll();

        return $this->render('SindilojasCobrancaBundle:Default:index.html.twig', array('name' => $name));
    }
}
