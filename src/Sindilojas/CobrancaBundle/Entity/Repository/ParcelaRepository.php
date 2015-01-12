<?php

namespace Sindilojas\CobrancaBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Description of ParcelaRepository
 *
 * @author Luciano
 */
class ParcelaRepository extends EntityRepository
{
  
    /**
     * 
     * @return array
     */
    public function getParcelasVencidas()
    {
        $query = $this->createQueryBuilder("P");
        
        $data = date("Y-m-d");
        
        $query->select("P, N, D, C, L")
                ->leftJoin("P.negociacao", "N")
                ->leftJoin("N.divida", "D")
                ->leftJoin("D.cliente", "C")
                ->leftJoin("D.loja", "L")
                ->andWhere($query->expr()->lt("P.vencimento", ":data"))
                ->andWhere($query->expr()->eq("P.pago", "0"));
        $query->setParameter("data", $data, \PDO::PARAM_STR);
        return $query->getQuery()->getResult();
    }
    
    /**
     * 
     * @return array
     */
    public function getParcela($id)
    {
        $query = $this->createQueryBuilder("P");
        
        $data = date("Y-m-d");
        
        $query->select("P, N, D, C, L")
                ->leftJoin("P.negociacao", "N")
                ->leftJoin("N.divida", "D")
                ->leftJoin("D.cliente", "C")
                ->leftJoin("D.loja", "L")
                ->andWhere($query->expr()->eq("P.id", ":id"));
        $query->setParameter("id", $id);
        
        return $query->getQuery()->getSingleResult();
    }

    /**
     * 
     * @param int $idDivida
     * @return float
     */
    public function getValorEmAberto($idDivida)
    {
        $queryN = $this->getEntityManager()->createQueryBuilder();
        $queryN->select($queryN->expr()->max("N.id"))
                ->from("Sindilojas\CobrancaBundle\Entity\Negociacao", "N")      
                ->andWhere($queryN->expr()->eq("N.divida", ":idDivida"));
        $queryN->setParameter("idDivida", $idDivida);
        $idNegociacao = $queryN->getQuery()->getSingleScalarResult();
        
        $query = $this->createQueryBuilder("P");

        $query->select("SUM(P.valor)")
                ->andWhere($query->expr()->eq("P.negociacao", ":idNegociacao"))
                ->andWhere($query->expr()->eq("P.pago", "0"));
        $query->setParameter("idNegociacao", $idNegociacao);
        
        return $query->getQuery()->getSingleScalarResult();
    }
}
