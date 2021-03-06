<?php

namespace Sindilojas\CobrancaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Cliente
 *
 * @ORM\Table(name="cliente")
 * @UniqueEntity(fields={"cpf"}, message="Este CPF já está cadastrado", repositoryMethod="uniqueEntity")
 * @UniqueEntity(fields={"cnpj"}, message="Este CNPJ já está cadastrado", repositoryMethod="uniqueEntity")
 * @ORM\Entity(repositoryClass="Sindilojas\CobrancaBundle\Entity\Repository\ClienteRepository")
 */
class Cliente
{
    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=50, nullable=false)
     */
    private $nome;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cobranca_judicial", type="integer", nullable=false)
     */
    private $cobrancajudicial = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="cpf", type="string", length=11, nullable=true)
     */
    private $cpf;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cnpj", type="string", length=14, nullable=true)
     */
    private $cnpj;

    /**
     * @var string
     *
     * @ORM\Column(name="rg", type="string", length=12, nullable=true)
     */
    private $rg;

    /**
     * @var string
     *
     * @ORM\Column(name="cep", type="string", length=10, nullable=true)
     */
    private $cep;

    /**
     * @var string
     *
     * @ORM\Column(name="uf", type="string", length=2, nullable=true)
     */
    private $uf;

    /**
     * @var string
     *
     * @ORM\Column(name="cidade", type="string", length=100, nullable=true)
     */
    private $cidade;

    /**
     * @var string
     *
     * @ORM\Column(name="bairro", type="string", length=50, nullable=true)
     */
    private $bairro;

    /**
     * @var string
     *
     * @ORM\Column(name="rua", type="string", length=150, nullable=true)
     */
    private $rua;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=30, nullable=true)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="complemento", type="string", length=30, nullable=true)
     */
    private $complemento;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=150, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone", type="string", length=20, nullable=true)
     */
    private $telefone;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone1", type="string", length=20, nullable=true)
     */
    private $telefone1;

    /**
     * @var string
     *
     * @ORM\Column(name="telefone2", type="string", length=20, nullable=true)
     */
    private $telefone2;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="nascimento", type="date", nullable=true)
     */
    private $nascimento;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    
    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany (targetEntity="Sindilojas\CobrancaBundle\Entity\Divida", mappedBy="cliente", cascade={"persist", "remove"})
     */
    private $dividas;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany (targetEntity="Sindilojas\CobrancaBundle\Entity\Registro", mappedBy="cliente", cascade={"persist", "remove"})
     */
    private $registros;

    
    public function __construct()
    {
        $this->setDividas(new ArrayCollection());
        $this->setRegistros(new ArrayCollection());
    }

    /**
     * 
     * @return ArrayCollection
     */
    function getRegistros()
    {
        return $this->registros;
    }

    /**
     * 
     * @param \Doctrine\Common\Collections\Collection $registros
     */
    function setRegistros(\Doctrine\Common\Collections\Collection $registros)
    {
        $this->registros = $registros;
    }
    
    /**
     * 
     * @return ArrayCollection
     */
    function getDividas()
    {
        return $this->dividas;
    }

    /**
     * 
     * @param \Doctrine\Common\Collections\Collection $dividas
     */
    function setDividas(\Doctrine\Common\Collections\Collection $dividas)
    {
        $this->dividas = $dividas;
    }
        
    /**
     * Set nome
     *
     * @param string $nome
     * @return Cliente
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
     * Set cpf
     *
     * @param string $cpf
     * @return Cliente
     */
    public function setCpf($cpf)
    {
        $this->cpf = preg_replace("/[^0-9]/", "", $cpf);

        return $this;
    }

    /**
     * Get cpf
     *
     * @return string 
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Set rg
     *
     * @param string $rg
     * @return Cliente
     */
    public function setRg($rg)
    {
        $this->rg = preg_replace("/[^0-9]/", "", $rg);;

        return $this;
    }

    /**
     * Get rg
     *
     * @return string 
     */
    public function getRg()
    {
        return $this->rg;
    }

    /**
     * Set cep
     *
     * @param string $cep
     * @return Cliente
     */
    public function setCep($cep)
    {
        $this->cep = $cep;

        return $this;
    }

    /**
     * Get cep
     *
     * @return string 
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * Set uf
     *
     * @param string $uf
     * @return Cliente
     */
    public function setUf($uf)
    {
        $this->uf = $uf;

        return $this;
    }

    /**
     * Get uf
     *
     * @return string 
     */
    public function getUf()
    {
        return $this->uf;
    }

    /**
     * Set cidade
     *
     * @param string $cidade
     * @return Cliente
     */
    public function setCidade($cidade)
    {
        $this->cidade = $cidade;

        return $this;
    }

    /**
     * Get cidade
     *
     * @return string 
     */
    public function getCidade()
    {
        return $this->cidade;
    }

    /**
     * Set bairro
     *
     * @param string $bairro
     * @return Cliente
     */
    public function setBairro($bairro)
    {
        $this->bairro = $bairro;

        return $this;
    }

    /**
     * Get bairro
     *
     * @return string 
     */
    public function getBairro()
    {
        return $this->bairro;
    }

    /**
     * Set rua
     *
     * @param string $rua
     * @return Cliente
     */
    public function setRua($rua)
    {
        $this->rua = $rua;

        return $this;
    }

    /**
     * Get rua
     *
     * @return string 
     */
    public function getRua()
    {
        return $this->rua;
    }

    /**
     * Set numero
     *
     * @param string $numero
     * @return Cliente
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set complemento
     *
     * @param string $complemento
     * @return Cliente
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;

        return $this;
    }

    /**
     * Get complemento
     *
     * @return string 
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Cliente
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telefone
     *
     * @param string $telefone
     * @return Cliente
     */
    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;

        return $this;
    }

    /**
     * Get telefone
     *
     * @return string 
     */
    public function getTelefone()
    {
        return $this->telefone;
    }

    /**
     * Set nascimento
     *
     * @param \DateTime $nascimento
     * @return Cliente
     */
    public function setNascimento($nascimento)
    {
        $this->nascimento = $nascimento;

        return $this;
    }

    /**
     * Get nascimento
     *
     * @return \DateTime 
     */
    public function getNascimento()
    {
        return $this->nascimento;
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
     * 
     * @return string
     */
    public function getEndereco()
    {
        $endereco = $this->getRua().", ".$this->getNumero();
        if ($this->getComplemento()) {
            $endereco .= ", ".$this->getComplemento();
        }
        $endereco .= " - ".$this->getCidade()."/".$this->getUf();
        return $endereco;
    }
    
    /**
     * 
     * @return string
     */
    function getCobrancajudicial()
    {
        return $this->cobrancajudicial;
    }

    /**
     * 
     * @param string $cobrancajudicial
     */
    function setCobrancajudicial($cobrancajudicial)
    {
        $this->cobrancajudicial = $cobrancajudicial;
    }
    
    /**
     * 
     * @return string
     */
    function getTelefone1()
    {
        return $this->telefone1;
    }

    /**
     * 
     * @return string
     */
    function getTelefone2()
    {
        return $this->telefone2;
    }

    /**
     * 
     * @param string $telefone1
     * @return \Sindilojas\CobrancaBundle\Entity\Cliente
     */
    function setTelefone1($telefone1)
    {
        $this->telefone1 = $telefone1;
        return $this;
    }

    /**
     * 
     * @param string $telefone2
     * @return \Sindilojas\CobrancaBundle\Entity\Cliente
     */
    function setTelefone2($telefone2)
    {
        $this->telefone2 = $telefone2;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getCnpj()
    {
        return $this->cnpj;
    }

    /**
     * 
     * @param string $cnpj
     */
    public function setCnpj($cnpj)
    {
        $this->cnpj = preg_replace("/[^0-9]/", "", $cnpj);
    }



    
}
