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
        $dividas = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Divida")->getDividasFromCpf($request->get("cpf"));
        
        $registros = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Registro")->getRegistros($request->get("cpf"));
        
        $renderDividas = $this->renderView("SindilojasCobrancaBundle::Cobranca\\dividas.html.twig", array("dividas"=>$dividas));;
        
        $renderRegistros = $this->renderView("SindilojasCobrancaBundle::Cobranca\\registros.html.twig", array("registros"=>$registros));;

        return new Response($renderDividas.$renderRegistros);
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
