<?php

namespace Sindilojas\CobrancaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parcela
 *
 * @ORM\Table(name="parcela", indexes={@ORM\Index(name="FK_parcela_negociacao", columns={"id_negociacao"})})
 * @ORM\Entity
 */
class Parcela
{
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
     * @var \DateTime
     *
     * @ORM\Column(name="vencimento", type="date", nullable=false)
     */
    private $vencimento;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pago", type="boolean", nullable=false)
     */
    private $pago;

    /**
     * @var boolean
     *
     * @ORM\Column(name="entrada", type="boolean", nullable=false)
     */
    private $entrada;

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
     * @ORM\ManyToOne(targetEntity="Sindilojas\CobrancaBundle\Entity\Negociacao")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_negociacao", referencedColumnName="id")
     * })
     */
    private $idNegociacao;



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
     * Set idNegociacao
     *
     * @param \Sindilojas\CobrancaBundle\Entity\Negociacao $idNegociacao
     * @return Parcela
     */
    public function setIdNegociacao(\Sindilojas\CobrancaBundle\Entity\Negociacao $idNegociacao = null)
    {
        $this->idNegociacao = $idNegociacao;

        return $this;
    }

    /**
     * Get idNegociacao
     *
     * @return \Sindilojas\CobrancaBundle\Entity\Negociacao 
     */
    public function getIdNegociacao()
    {
        return $this->idNegociacao;
    }
}
