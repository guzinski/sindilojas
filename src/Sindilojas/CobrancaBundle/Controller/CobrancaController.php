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
        $valor          = 0;
        $idDivida       = $request->request->getInt("id");
        $renegociacao   = $request->request->getInt("renegociacao", 0);
        $negociacao     = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Divida")->getNegociacao($idDivida);      
        $divida         = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Divida")->find($idDivida);
        if ($renegociacao) {
            $valor = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Parcela")->getValorEmAberto($idDivida);
        }
        
        $render         = $this->renderView("SindilojasCobrancaBundle::Cobranca\\negociacao.html.twig", array('valor'=>$valor,'divida'=>$divida, 'negociacao'=>$negociacao, "renegociacao"=>$renegociacao));
        
        
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
     * @Route("/cobranca/promissoria/{idNegociacao}", name="_promissoria")
     * 
     * @Template
     * @param int $idNegociacao
     * @return Response
     */
    public function promissoriaAction($idNegociacao)
    {
        $negociacao = $this->getDoctrine()
                        ->getRepository("Sindilojas\CobrancaBundle\Entity\Negociacao")
                        ->find($idNegociacao);

        return array("negociacao"=>$negociacao, "hoje"=> new \DateTime("now"));
    }
    
    
    
    /**
     * @Route("/cobranca/recibo/{idParcela}", name="_recibo")
     * 
     * @Template
     * @param int $idParcela
     * @return Response
     */
    public function reciboAction($idParcela)
    {
        $parcela = $this->getDoctrine()
                ->getRepository("Sindilojas\CobrancaBundle\Entity\Parcela")
                ->getParcela($idParcela);
        
        $hoje = new \DateTime("now");
        
        return array(
            "numeroParcela"=>$parcela->getNumero()+1,
            "totalParcelas"=>$parcela->getNegociacao()->getParcelas()->count(),
            "clienteNome" => $parcela->getNegociacao()->getDivida()->getCliente()->getNome(),
            "clienteCpf" => $this->mask($parcela->getNegociacao()->getDivida()->getCliente()->getCpf(), "###.###.###/##"),
            "hoje" => $hoje->format('d/m/Y'),
            "valor"=>$parcela->getValorPago(),
            "valorExtenso"=>$this->valorPorExtenso($parcela->getValorPago())
            );
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

    function valorPorExtenso($valor = 0, $complemento = true)
    {
        $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

        $z = 0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        for ($i = 0; $i < count($inteiro); $i++) {
            for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++) {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        $rt = "";
        for ($i = 0; $i < count($inteiro); $i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            if ($complemento == true) {
                $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
                if ($valor == "000") {
                    $z++;
                } elseif ($z > 0) {
                    $z--;
                }
                
                if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) {
                    $r .= (($z > 1) ? " de " : "") . $plural[$t];
                }
            }
            if ($r) {
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
            }
        }

        return($rt ? trim($rt) : "zero");
    }

}