<?php

namespace Sindilojas\CobrancaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    private $idDivida;



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
     * Set idDivida
     *
     * @param \Sindilojas\CobrancaBundle\Entity\Divida $idDivida
     * @return Negociacao
     */
    public function setIdDivida(\Sindilojas\CobrancaBundle\Entity\Divida $idDivida = null)
    {
        $this->idDivida = $idDivida;

        return $this;
    }

    /**
     * Get idDivida
     *
     * @return \Sindilojas\CobrancaBundle\Entity\Divida 
     */
    public function getIdDivida()
    {
        return $this->idDivida;
    }
}
