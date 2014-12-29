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
        $query = $this->createQueryBuilder("P");

        $query->select("SUM(P.valor)")
                ->leftJoin("P.negociacao", "N")
                ->leftJoin("N.divida", "D")
                ->andWhere($query->expr()->eq("D.id", ":idDivida"))
                ->andWhere($query->expr()->eq("P.pago", "0"));
        $query->setParameter("idDivida", $idDivida);
        
        return $query->getQuery()->getSingleScalarResult();
    }
}
