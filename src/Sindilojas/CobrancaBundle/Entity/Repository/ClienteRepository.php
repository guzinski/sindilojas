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
                                    ->orWhere($query->expr()->like("C.telefone", ":busca"))
                                    ->orWhere($query->expr()->like("C.telefone1", ":busca"))
                                    ->orWhere($query->expr()->like("C.telefone2", ":busca"))
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
    
    /**
     * 
     * @param string $tipo
     * @param int $loja
     * @return array
     */
    public function getRelatorioClientes($tipo, $loja)
    {
        $where = "";
        if ($tipo == "atraso") {
            $where .= "AND p3.vencimento < CURRENT_DATE() AND p3.pago=0 ";
        } else if ($tipo == "andamento") {
            $where .= "AND n.id>0 ";
        } else if ($tipo == "judicial") {
            $where .= "AND c.cobranca_judicial=1 ";
        }
        if ($loja>0) {
            $where .= "AND l.id=$loja";
        }
        $query = "SELECT c.nome, c.cpf, c.telefone, l.nome as loja, r.texto, p.valor_pago, p.data_pagamento
                        FROM cliente c
                            LEFT JOIN divida d
                                    ON d.id_cliente=c.id
                            LEFT JOIN (SELECT * FROM negociacao n1 ORDER BY n1.id DESC) n
                                    ON n.id_divida=d.id
                            LEFT JOIn loja l
                                    ON l.id=d.id_loja
                            LEFT JOIN (SELECT * FROM registro r1 ORDER BY r1.data DESC) as r
                                    ON r.id_cliente=c.id
                            LEFT JOIN (SELECT * FROM parcela p1 ORDER BY p1.id DESC) p
                                    ON p.id_negociacao=n.id
                                            AND p.pago=1
                            LEFT JOIN (SELECT * FROM parcela p2 ORDER BY p2.id DESC) p3
                                    ON p3.id_negociacao=n.id
                        WHERE 1=1
                            $where
                        GROUP BY d.id, c.id";
        return $this->getEntityManager()->getConnection()->fetchAll($query);
    }


    
}
