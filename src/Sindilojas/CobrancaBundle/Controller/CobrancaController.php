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
        $cliente = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Cliente")->findOneBy(array("cpf"=>preg_replace("/[^0-9]/", "", $request->get("cpf"))));
        
        if (!empty($cliente)) {
            $dividas = $cliente->getDividas();
            $registros = $cliente->getRegistros();
            $renderDividas = $this->renderView("SindilojasCobrancaBundle::Cobranca\\dividas.html.twig", array("dividas"=>$dividas));
            $renderRegistros = $this->renderView("SindilojasCobrancaBundle::Cobranca\\registros.html.twig", array("registros"=>$registros));
            $render = $renderDividas.$renderRegistros;
        } else {
            $render = $this->renderView("SindilojasCobrancaBundle::Cobranca\\notFound.html.twig");
        }       
        return new Response($render);
    }
    
    /**
     * @Route("/cobranca/detalhes", name="_detalhes_divida")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function dividasDetalhesAction(Request $request)
    {
        $idDivida = $request->request->getInt("id");
        
        $negociacao = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Negociacao")->findBy(array('divida'=>$idDivida));
        
        $divida = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Divida")->find($idDivida);

        $render = $this->renderView("SindilojasCobrancaBundle::Cobranca\\negociacao.html.twig", array('divida'=>$divida, 'negociacao'=>$negociacao));;

        return new Response($render);
    }

}
