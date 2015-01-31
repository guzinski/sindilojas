<?php

namespace Sindilojas\CobrancaBundle\Twig;

/**
 * Description of newPHPClass
 *
 * @author Luciano
 */
class AppTwigFilters extends \Twig_Extension
{
    
    /**
     * 
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('mask', array($this, 'maskFilter')),
            new \Twig_SimpleFilter('valorExtenso', array($this, 'valorExtensoFilter')),
            new \Twig_SimpleFilter('getStringMes', array($this, 'getStringMesFilter')),
        );
    }

    /**
     * Ex: de Uso. para mascara de CPF passar o CPf e a maáscara ###.###.###-##
     * usar "#"
     * 
     * @param string $val
     * @param string $mask
     * @return string
     */
    public function maskFilter($val, $mask)
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
    
    /**
     * Escre o valor por extenso
     * 
     * @param float $valor
     * @param boolean $complemento
     * @return string
     */
    public function valorExtensoFilter($valor, $complemento = true)
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

    
    public function getStringMesFilter($mes)
    {
        $meses = array(
            1=>"Janeiro",
            2=>"Fevereiro",
            3=>"Março",
            4=>"Abril",
            5=>"Maio",
            6=>"Junho",
            7=>"julho",
            8=>"Agosto",
            9=>"Setembro",
            10=>"Outubro",
            11=>"Novembro",
            12=>"Dezembro",
        );

        return $meses[$mes];
    }

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return 'app_twig_filters';
    }
    
    
}
