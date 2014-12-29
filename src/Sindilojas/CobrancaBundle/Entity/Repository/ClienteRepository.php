<?php

namespace Sindilojas\CobrancaBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;


/**
 * Description of ClienteRepository
 *
 * @author Luciano
 */
class ClienteRepository extends EntityRepository
{
    /**
     * 
     * @param string $busca
     * @param int $maxResults
     * @param int $firstResult
     * @return type
     */
    public function getClientes($busca, $maxResults, $firstResult)
    {
        $query = $this->createQueryBuilder("C");
        
        $query->select("C");
        if (!empty($busca)) {
            $query->andWhere($query->orWhere($query->expr()->like("C.nome", ":busca"))
                                    ->orWhere($query->expr()->like("C.cpf", ":busca"))
                                    ->orWhere($query->expr()->like("C.cidade", ":busca"))
                                    ->getDQLPart("where"));
            $query->setParameter("busca", "%{$busca}%");
        }
        
        if (($maxResults+$firstResult)>0) {
            $query->setFirstResult($firstResult)
                    ->setMaxResults($maxResults);
        }
                
        return $query->getQuery()->getResult();
    }
    
    /**
     * 
     * @return array
     */
    public function count($busca = "")
    {
        $query = $this->createQueryBuilder("C");
        
        $query->select("COUNT(C.id)");
        
        if (!empty($busca)) {
            $query->andWhere($query->orWhere($query->expr()->like("C.nome", ":busca"))
                                    ->orWhere($query->expr()->like("C.cpf", ":busca"))
                                    ->orWhere($query->expr()->like("C.cidade", ":busca"))
                                    ->getDQLPart("where"));
            $query->setParameter("busca", "%{$busca}%");
        }
        
        return $query->getQuery()->getSingleScalarResult();
    }
    

    
}
