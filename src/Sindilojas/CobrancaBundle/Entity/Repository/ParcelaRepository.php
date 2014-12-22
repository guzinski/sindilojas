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
                ->andWhere($query->expr()->lt("P.vencimento", ":data"));
        $query->setParameter("data", $data, \PDO::PARAM_STR);
        return $query->getQuery()->getResult();
    }
    
}
