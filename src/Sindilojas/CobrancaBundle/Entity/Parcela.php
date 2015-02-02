<?php

namespace Sindilojas\CobrancaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parcela
 *
 * @ORM\Table(name="parcela", indexes={@ORM\Index(name="FK_parcela_negociacao", columns={"id_negociacao"})})
 * @ORM\Entity(repositoryClass="Sindilojas\CobrancaBundle\Entity\Repository\ParcelaRepository")
 */
class Parcela
{
    
    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=1, nullable=true)
     */
    private $tipo;
    
    /**
     * @var string
     *
     * @ORM\Column(name="modo", type="string", length=1, nullable=true)
     */
    private $modo;

    
    /**
     * @var integer
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="float", precision=10, scale=0, nullable=false)
     */
    private $valor;
    
    /**
     * @var float
     *
     * @ORM\Column(name="valor_pago", type="float", precision=10, scale=0, nullable=true)
     */
    private $valorPago;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="vencimento", type="date", nullable=false)
     */
    private $vencimento;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_pagamento", type="date", nullable=true)
     */
    private $dataPagamento = null;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pago", type="boolean", nullable=false)
     */
    private $pago = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(name="entrada", type="boolean", nullable=false)
     */
    private $entrada = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Sindilojas\CobrancaBundle\Entity\Negociacao
     *
     * @ORM\ManyToOne(targetEntity="Sindilojas\CobrancaBundle\Entity\Negociacao", inversedBy="parcelas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_negociacao", referencedColumnName="id")
     * })
     */
    private $negociacao;



    /**
     * Set numero
     *
     * @param integer $numero
     * @return Parcela
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set valor
     *
     * @param float $valor
     * @return Parcela
     */
    public function setValor($valor)
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float 
     */
    public function getValor()
    {
        return $this->valor;
    }
    
    /**
     * Set valor
     *
     * @param float $valor
     * @return Parcela
     */
    public function setValorPago($valor)
    {
        $this->valorPago = $valor;

        return $this;
    }

    /**
     * Get valor
     *
     * @return float 
     */
    public function getValorPago()
    {
        return $this->valorPago;
    }


    /**
     * Set vencimento
     *
     * @param \DateTime $vencimento
     * @return Parcela
     */
    public function setVencimento($vencimento)
    {
        $this->vencimento = $vencimento;

        return $this;
    }

    /**
     * Get vencimento
     *
     * @return \DateTime 
     */
    public function getVencimento()
    {
        return $this->vencimento;
    }

    /**
     * Set pago
     *
     * @param boolean $pago
     * @return Parcela
     */
    public function setPago($pago)
    {
        $this->pago = $pago;

        return $this;
    }

    /**
     * Get pago
     *
     * @return boolean 
     */
    public function getPago()
    {
        return $this->pago;
    }

    /**
     * Set entrada
     *
     * @param boolean $entrada
     * @return Parcela
     */
    public function setEntrada($entrada)
    {
        $this->entrada = $entrada;

        return $this;
    }

    /**
     * Get entrada
     *
     * @return boolean 
     */
    public function getEntrada()
    {
        return $this->entrada;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set negociacao
     *
     * @param \Sindilojas\CobrancaBundle\Entity\Negociacao $negociacao
     * @return Parcela
     */
    public function setNegociacao(\Sindilojas\CobrancaBundle\Entity\Negociacao $negociacao)
    {
        $this->negociacao = $negociacao;

        return $this;
    }

    /**
     * Get negociacao
     *
     * @return \Sindilojas\CobrancaBundle\Entity\Negociacao 
     */
    public function getNegociacao()
    {
        return $this->negociacao;
    }
    
    /**
     * 
     * @return \DateTime
     */
    function getDataPagamento()
    {
        return $this->dataPagamento;
    }

    /**
     * 
     * @param \DateTime $dataPagamento
     */
    function setDataPagamento(\DateTime $dataPagamento = null)
    {
        $this->dataPagamento = $dataPagamento;
        
        return $this;
    }

    /**
     * 
     * @return string
     */
    function getTipo()
    {
        return $this->tipo;
    }

    /**
     * 
     * @param string $tipo
     * @return \Sindilojas\CobrancaBundle\Entity\Parcela
     */
    function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }


    /**
     * 
     * @return string
     */
    function getModo()
    {
        return $this->modo;
    }

    /**
     * 
     * @param string $modo
     * @return \Sindilojas\CobrancaBundle\Entity\Parcela
     */
    function setModo($modo)
    {
        $this->modo = $modo;
        return $this;
    }



    
}
