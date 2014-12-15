<?php

namespace Sindilojas\CobrancaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Negociacao
 *
 * @ORM\Table(name="negociacao", indexes={@ORM\Index(name="FK_negociacao_divida", columns={"id_divida"})})
 * @ORM\Entity
 */
class Negociacao
{
    /**
     * @var float
     *
     * @ORM\Column(name="valor", type="float", precision=10, scale=0, nullable=true)
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
     * @var \Sindilojas\CobrancaBundle\Entity\Divida
     *
     * @ORM\ManyToOne(targetEntity="Sindilojas\CobrancaBundle\Entity\Divida")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_divida", referencedColumnName="id")
     * })
     */
    private $divida;

    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany (targetEntity="Sindilojas\CobrancaBundle\Entity\Parcela", mappedBy="cliente", cascade={"persist", "remove"})
     */
    private $parcelas;

    
    public function __construct()
    {
        $this->setParcelas(new ArrayCollection());
    }


    /**
     * Set valor
     *
     * @param float $valor
     * @return Negociacao
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set divida
     *
     * @param \Sindilojas\CobrancaBundle\Entity\Divida $divida
     * @return Negociacao
     */
    public function setDivida(\Sindilojas\CobrancaBundle\Entity\Divida $divida = null)
    {
        $this->divida = $divida;

        return $this;
    }

    /**
     * Get divida
     *
     * @return \Sindilojas\CobrancaBundle\Entity\Divida 
     */
    public function getIdDivida()
    {
        return $this->divida;
    }
    
    /**
     * 
     * @return type
     */
    function getParcelas()
    {
        return $this->parcelas;
    }

    /**
     * 
     * @param \Doctrine\Common\Collections\Collection $parcelas
     */
    function setParcelas(\Doctrine\Common\Collections\Collection $parcelas)
    {
        $this->parcelas = $parcelas;
    }


}
