<?php

namespace Sindilojas\CobrancaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


/**
 * Description of CobrancaController
 *
 * @author Luciano
 */
class CobrancaController extends Controller
{
    /**
     * @Route("/cobranca", name="_cobranca")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
    
    /**
     * @Route("/cobranca/dividas", name="_cobranca_dividas")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function dividasAction(Request $request)
    {
        
        $rep = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Divida");
        
        $dividas = $rep->getDividasFromCpf($request->get("cpf"));
        
        $render = $this->renderView("SindilojasCobrancaBundle::Cobranca\dividas.html.twig", array("dividas"=>$dividas));;

        return new Response($render);
    }
}
