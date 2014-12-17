<?php

namespace Sindilojas\CobrancaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sindilojas\CobrancaBundle\Entity\Registro;
use Sindilojas\CobrancaBundle\Entity\Parcela;
use Sindilojas\CobrancaBundle\Entity\Negociacao;

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
            $renderDividas = $this->renderView("SindilojasCobrancaBundle::Cobranca\\dividas.html.twig", array("dividas"=>$dividas));
            $renderRegistros = $this->renderView("SindilojasCobrancaBundle::Cobranca\\registros.html.twig", array("cliente"=>$cliente));
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
        $idDivida   = $request->request->getInt("id");
        $negociacao = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Negociacao")->findOneBy(array('divida'=>$idDivida));        
        $divida     = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Divida")->find($idDivida);
        $render     = $this->renderView("SindilojasCobrancaBundle::Cobranca\\negociacao.html.twig", array('divida'=>$divida, 'negociacao'=>$negociacao));

        return new Response($render);
    }

    /**
     * @Route("/cobranca/inserir/registro", name="_inserir_registro")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function inseriRegistroAction(Request $request)
    {
        $em         = $this->getDoctrine()->getManager();
        $idCliente  = $request->request->getInt("id");
        $texto      = $request->get("texto");
        
        $cliente    = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Cliente")->find($idCliente);
        
        $novoRegistro = new Registro();
        $novoRegistro->setTexto($texto);        
        $novoRegistro->setCliente($cliente);
        $em->persist($novoRegistro);
        $em->flush();
        
        $render = $this->renderView("SindilojasCobrancaBundle::Cobranca\\registros.html.twig", array("cliente"=>$cliente));

        
        return new Response($render);
    }

    /**
     * @Route("/cobranca/inserir/negociacao", name="_cobranca_inseri_negociacao")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function inseriNegocicaoAction(Request $request)
    {
        $em             = $this->getDoctrine()->getManager();
        $idDivida       = $request->request->getInt("idDivida");
        $valorEntrada   = $request->get("entrada");
        $numParcelas    = $request->get("numParcelas");
        $vencimento     = $request->get("vencimento");
        $valorTotal     = $request->get("valorreal");
        
        $divida = $em->getRepository("Sindilojas\CobrancaBundle\Entity\Divida")->find($idDivida);        
        
        $negociacao = new Negociacao();
        $negociacao->setDivida($divida);

        $dataVencimento = \DateTime::createFromFormat("d/m/Y", $vencimento);
        $valorParcela = ($valorTotal-$valorEntrada)/$numParcelas;
        
        $entrada = new Parcela();
        $entrada->setEntrada(1);
        $entrada->setValor($valorEntrada);
        $entrada->setVencimento(new \DateTime("now"));
        $entrada->setNegociacao($negociacao);
        $negociacao->getParcelas()->add($entrada);

        for ($i=1; $i<=$numParcelas; $i++) {
            $dataVencimento->add(new \DateInterval("P1M"));
            $parcela = new Parcela();
            $parcela->setVencimento(clone $dataVencimento);
            $parcela->setValor($valorParcela);
            $parcela->setNumero($i);
            $parcela->setNegociacao($negociacao);
            $negociacao->getParcelas()->add($parcela);
        }
        
        $em->persist($negociacao);
        $em->flush();
        
        $render = $this->renderView("SindilojasCobrancaBundle::Cobranca\\negociacao.html.twig", array('divida'=>$divida, 'negociacao'=>$negociacao));
        
        return new Response($render);
    }
    
    /**
     * @Route("/cobranca/dar/baixa", name="_dar_baixa")
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function darBaixaAction(Request $request) 
    {
        $em             = $this->getDoctrine()->getManager();
        $idParcela       = $request->request->getInt("id");
        $parcela = $em->getRepository("Sindilojas\CobrancaBundle\Entity\Parcela")->find($idParcela);
        
        $parcela->setPago(1);
        
        $em->persist($parcela);
        $em->flush();
        
        $render = $this->renderView("SindilojasCobrancaBundle::Cobranca\\negociacao.html.twig", array('divida'=>$parcela->getNegociacao()->getDivida(), 'negociacao'=>$parcela->getNegociacao()));
        
        return new Response($render);
    }
    
    
}