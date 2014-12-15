<?php

namespace Sindilojas\CobrancaBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * Description of RegistroRepository
 *
 * @author Luciano
 */
class RegistroRepository extends EntityRepository
{
    
    /**
     * 
     * @param string $idDivida
     * @return array
     */
    public function getRegistros($cpf)
    {
        $query = $this->createQueryBuilder("R");
        
        $query->select("R")->leftJoin("R.cliente", "C")
                ->where($query->expr()->eq("C.cpf", ":cpf"));
        $query->setParameter("cpf", $cpf);
        
        return $query->getQuery()->getResult();
    }
    
    
}
