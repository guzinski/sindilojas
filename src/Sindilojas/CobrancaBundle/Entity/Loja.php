<?php

namespace Sindilojas\CobrancaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Loja
 *
 * @ORM\Table(name="loja")
 * @ORM\Entity
 */
class Loja
{
    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=100, nullable=false)
     */
    private $nome;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cnpj", type="string", length=14, nullable=false)
     */
    private $cnpj;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set nome
     *
     * @param string $nome
     * @return Loja
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome()
    {
        return $this->nome;
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
    
    public function __toString()
    {
        return $this->getNome();
    }
    
    /**
     * 
     * @return string
     */
    function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * 
     * @param string $cnpj
     */
    function setCnpj($cnpj)
    {
        $this->cnpj = $cnpj;
        
        return $this;
    }


}
