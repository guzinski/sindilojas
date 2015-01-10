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
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=1, nullable=false)
     */
    private $tipo = "A";


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
     * @ORM\OneToMany (targetEntity="Sindilojas\CobrancaBundle\Entity\Parcela", mappedBy="negociacao", cascade={"persist", "remove"}, fetch="EXTRA_LAZY")
     */
    private $parcelas;

    
    public function __construct()
    {
        $this->setParcelas(new ArrayCollection());
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
    public function getDivida()
    {
        return $this->divida;
    }
    
    /**
     * 
     * @return type
     */
    public function getParcelas()
    {
        return $this->parcelas;
    }

    /**
     * 
     * @param \Doctrine\Common\Collections\Collection $parcelas
     */
    public function setParcelas(\Doctrine\Common\Collections\Collection $parcelas)
    {
        $this->parcelas = $parcelas;
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
     */
    function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }


    

}
