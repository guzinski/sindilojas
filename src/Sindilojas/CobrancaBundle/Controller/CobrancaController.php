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
use Sindilojas\CobrancaBundle\Entity\Divida;
use PhpOffice\PhpWord\PhpWord;

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
     * 
     * @return array
     */
    public function indexAction()
    {
        return array();
    }
    
    /**
     * @Route("/cobranca/dividas", name="_cobranca_dividas")
     * 
     * @param Request $request
     * @return Response
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
     * @param Request $request
     * @return Response
     */
    public function dividasDetalhesAction(Request $request)
    {
        $idDivida       = $request->request->getInt("id");
        $renegociacao   = $request->request->getInt("renegociacao", 0);
        $negociacao     = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Divida")->getNegociacao($idDivida);        
        $divida         = $negociacao->getDivida();
        $render         = $this->renderView("SindilojasCobrancaBundle::Cobranca\\negociacao.html.twig", array('divida'=>$divida, 'negociacao'=>$negociacao, "renegociacao"=>$renegociacao));

        return new Response($render);
    }

    /**
     * @Route("/cobranca/inserir/registro", name="_inserir_registro")
     * 
     * @param Request $request
     * @return Response
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
     * @param Request $request
     * @return Response
     */
    public function inseriNegocicaoAction(Request $request)
    {
        $em             = $this->getDoctrine()->getManager();
        $idDivida       = $request->request->getInt("idDivida");
        $valorEntrada   = $request->get("entrada");
        $numParcelas    = $request->get("numParcelas");
        $vencimento     = $request->get("vencimento");
        $valorTotal     = $request->get("valorAtual");
        $tipo           = $request->get("tipo");
        
        $divida = $em->getRepository("Sindilojas\CobrancaBundle\Entity\Divida")->find($idDivida);        
        
        $negociacao = new Negociacao();
        $negociacao->setDivida($divida);
        $negociacao->setTipo($tipo);
        
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
        
        $render = $this->renderView("SindilojasCobrancaBundle::Cobranca\\negociacao.html.twig", array('divida'=>$divida, 'negociacao'=>$negociacao, "renegociacao"=>0));
        
        return new Response($render);
    }
    
    /**
     * @Route("/cobranca/dar/baixa", name="_dar_baixa")
     * 
     * @param Request $request
     * @return Response
     */
    public function darBaixaAction(Request $request) 
    {
        $em         = $this->getDoctrine()->getManager();
        $idParcela  = $request->request->getInt("id");
        $data       = $request->request->get("data");
        $valor      = $request->request->get("valor");
        $parcela    = $em->getRepository("Sindilojas\CobrancaBundle\Entity\Parcela")->find($idParcela);
        
        $parcela->setPago(1);
        $parcela->setDataPagamento(\DateTime::createFromFormat("d/m/Y", $data));
        $parcela->setValorPago((float) $valor);
        $em->persist($parcela);
        $em->flush();
        
        $render = $this->renderView("SindilojasCobrancaBundle::Cobranca\\negociacao.html.twig", array('divida'=>$parcela->getNegociacao()->getDivida(), 'negociacao'=>$parcela->getNegociacao(), "renegociacao"=>0));
        
        return new Response($render);
    }
    
    /**
     * @Route("/cobranca/gerar/nota", name="_gerar_nota")
     * 
     * @param Request $request
     * @return Response
     */
    public function geraNotaAction(Request $request)
    {
        $idParcela = $request->request->getInt("id");
        $parcela = $this->getDoctrine()
                ->getRepository("Sindilojas\CobrancaBundle\Entity\Parcela")
                ->getParcela($idParcela);
        
        $PHPWord = new PhpWord();
        try {
            $document = $PHPWord->loadTemplate('documentos/nota.docx');
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
        
        $document->setValue('data_vencimento', $parcela->getVencimento()->format('d/m/Y'));
        $document->setValue('data', $parcela->getVencimento()->format('d/m/Y'));
        $document->setValue('nome_cliente', $parcela->getNegociacao()->getDivida()->getCliente()->getNome());
        $document->setValue('loja', $parcela->getNegociacao()->getDivida()->getLoja()->getNome());
        $document->setValue('cpf_cliente', $this->mask($parcela->getNegociacao()->getDivida()->getCliente()->getCpf(), "###.###.###-##"));
        $document->setValue('valor_parcela', number_format($parcela->getValor(), 2, ",", "."));
        $document->setValue('cnpj', $this->mask($parcela->getNegociacao()->getDivida()->getLoja()->getCnpj(), "##.###.###/####-##"));
        $document->setValue('endereço_cliente', $parcela->getNegociacao()->getDivida()->getCliente()->getEndereco());
        $document->setValue('total_parcelas', $parcela->getNegociacao()->getParcelas()->count());
        $document->setValue('num_parcela', $parcela->getNumero());
        $document->saveAs("documentos/nota_{$idParcela}.docx");

        return new Response("documentos/nota_{$idParcela}.docx");
    }
    
    
    
    /**
     * @Route("/cobranca/gerar/recibo", name="_gerar_recibo")
     * 
     * @param Request $request
     * @return Response
     */
    public function gerarReciboAction(Request $request)
    {
        $idParcela = $request->request->getInt("id");
        $parcela = $this->getDoctrine()
                ->getRepository("Sindilojas\CobrancaBundle\Entity\Parcela")
                ->getParcela($idParcela);
        
        $hoje = new \DateTime("now");
        
        $PHPWord = new PhpWord();
        try {
            $document = $PHPWord->loadTemplate('documentos/recibo.docx');
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
        
        $document->setValue('data_pagamento', $parcela->getDataPagamento()->format('d/m/Y'));
        $document->setValue('data', $hoje->format('d/m/Y'));
        $document->setValue('cliente', $parcela->getNegociacao()->getDivida()->getCliente()->getNome());
        $document->setValue('loja', $parcela->getNegociacao()->getDivida()->getLoja()->getNome());
        $document->setValue('valor', number_format($parcela->getValor(), 2, ",", "."));
        $document->setValue('valor_reduzido', number_format($parcela->getValor()*0.2, 2, ",", "."));
        $document->saveAs("documentos/recibo_{$idParcela}.docx");

        return new Response("documentos/recibo_{$idParcela}.docx");
    }


    /**
     * @Route("/cobranca/renegociar", name="_renegociar")
     * 
     * @param Request $request
     * @return Response
     */
    public function renegociarAction(Request $request)
    {
        $idDivida = $request->request->getInt("idDivida");
        $doctrine = $this->getDoctrine();
        
        $valor = $doctrine
                        ->getRepository("Sindilojas\CobrancaBundle\Entity\Parcela")
                        ->getValorEmAberto($idDivida);
        
        $divida = $doctrine
                        ->getRepository("Sindilojas\CobrancaBundle\Entity\Divida")
                        ->find($idDivida);
        
        $novaDivida = new Divida();
        
        $novaDivida->setValor($valor)
                    ->setCliente($divida->getCliente())
                    ->setLoja($divida->getLoja())
                    ->setVencimento(new \DateTime("now"));
        
        $doctrine->getManager()->persist($novaDivida);
        $doctrine->getManager()->flush();
        
        return new Response($novaDivida->getId());
    }

    
    private function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#' && isset($val[$k])) {
                    $maskared .= $val[$k++];
            } elseif(isset($mask[$i])) {
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    
}