<?php

namespace Sindilojas\CobrancaBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;



/**
 * Description of DividaRepository
 *
 * @author Luciano
 */
class DividaRepository extends EntityRepository
{
    /**
     * 
     * @param string $cpf
     * @return array
     */
    public function getDividasFromCpf($cpf)
    {
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        
        $query = $this->createQueryBuilder("d");
        
        $query->select("d,c")->leftJoin("d.cliente", "c")
                ->andWhere($query->expr()->eq("c.cpf", ":cpf"))
                ->setParameter("cpf", $cpf);
                    
        
        return $query->getQuery()->getResult();
    }
    
    
    
    
}
