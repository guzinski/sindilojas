<?php

namespace Sindilojas\CobrancaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Divida
 *
 * @ORM\Table(name="divida", indexes={@ORM\Index(name="FK_divida_cliente", columns={"id_cliente"}), @ORM\Index(name="FK_divida_loja", columns={"id_loja"})})
 * @ORM\Entity
 */
class Divida
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="vencimento", type="date", nullable=false)
     */
    private $vencimento;

    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="float", precision=10, scale=0, nullable=false)
     */
    private $valor;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Sindilojas\CobrancaBundle\Entity\Loja
     *
     * @ORM\ManyToOne(targetEntity="Sindilojas\CobrancaBundle\Entity\Loja")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_loja", referencedColumnName="id")
     * })
     */
    private $loja;

    /**
     * @var \Sindilojas\CobrancaBundle\Entity\Cliente
     *
     * @ORM\ManyToOne(targetEntity="Sindilojas\CobrancaBundle\Entity\Cliente", inversedBy="dividas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cliente", referencedColumnName="id")
     * })
     */
    private $cliente;



    /**
     * Set vencimento
     *
     * @param \DateTime $vencimento
     * @return Divida
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
     * Set valor
     *
     * @param float $valor
     * @return Divida
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        die($this->valor);
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set loja
     *
     * @param \Sindilojas\CobrancaBundle\Entity\Loja $loja
     * @return Divida
     */
    public function setLoja(\Sindilojas\CobrancaBundle\Entity\Loja $loja = null)
    {
        $this->loja = $loja;

        return $this;
    }

    /**
     * Get loja
     *
     * @return \Sindilojas\CobrancaBundle\Entity\Loja 
     */
    public function getLoja()
    {
        return $this->loja;
    }

    /**
     * Set cliente
     *
     * @param \Sindilojas\CobrancaBundle\Entity\Cliente $cliente
     * @return Divida
     */
    public function setCliente(\Sindilojas\CobrancaBundle\Entity\Cliente $cliente = null)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \Sindilojas\CobrancaBundle\Entity\Cliente 
     */
    public function getCliente()
    {
        return $this->cliente;
    }
    
    public function __toString()
    {
        return (string) $this->getValor();
    }

}
