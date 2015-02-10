<?php

namespace Sindilojas\CobrancaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Description of RelatorioController
 *
 * @author Luciano
 */
class RelatorioController extends Controller
{
    
    /**
     * 
     * @Route("/relatorios", name="_relatorios")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
    
    /**
     * 
     * @Route("/html/form/recibo", name="_html_form_recibo")
     * @Template()
     */
    public function htmlFormReciboAction()
    {
        $lojas = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Loja")->findBy(array(), array("nome" => "ASC"));
        return array("lojas"=>$lojas);
    }
    
    /**
     * 
     * @Route("/html/form/cobranca", name="_html_form_cobranca")
     * @Template()
     */
    public function htmlFormCobrancaAction()
    {
        $lojas = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Loja")->findBy(array(), array("nome"=>"ASC"));
        return array("lojas"=>$lojas);
    }
    
    /**
     * 
     * @Route("/html/form/clientes", name="_html_form_clientes")
     * @Template()
     */
    public function htmlFormClientesAction()
    {
        $lojas = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Loja")->findBy(array(), array("nome"=>"ASC"));
        return array("lojas"=>$lojas);
    }
    
    /**
     * 
     * @Route("/relatorio/recibo/loja/{idLoja}/{mes}/{ano}", name="_recibo_loja")
     * @Template()
     * @param int $idLoja
     * @param int $mes
     * @param int $ano
     */
    public function reciboLojaAction($idLoja = null, $mes = null, $ano = null)
    {
        $parcelas = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Parcela")->getParcelasFromLoja($idLoja, $mes, $ano);
        $loja = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Loja")->find($idLoja);
        
        return array(
                "parcelas"=>$parcelas,
                "loja"=>$loja,
                "hoje"=>new \DateTime("now"),
                "ano"=>$ano,
                "mes"=>$mes
            );
    }
    
    /**
     * @Route("/relatorio/cobranca/loja/{mes}/{ano}", name="_cobranca_loja")
     * @Template()
     * @param int $mes
     * @param int $ano
     * @return array
     */
    public function cobrancaLojaAction($mes = null, $ano = null)
    {
        $repository = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Parcela");
        $lojas      = $repository->getRelatorioMensal($mes, $ano);
        $parcelas   = $repository->getParcelasMesAno($mes, $ano);

        return array("mes"=>$mes, "ano"=>$ano, "lojas"=>$lojas, "parcelas"=>$parcelas);
    }
    
    /**
     * @Route("/relatorio/clientes/{tipo}/{loja}", name="_relatorio_clientes")
     * @Template()
     * @param int $mes
     * @param int $ano
     * @return array
     */
    public function relatorioClientesAction($tipo = null, $loja = null)
    {
        $repository = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Cliente");
        
        return array("clientes"=>$repository->getRelatorioClientes($tipo, $loja));
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
