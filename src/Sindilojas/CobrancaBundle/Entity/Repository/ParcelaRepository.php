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
        
        $query = "SELECT d.id_cliente, c.nome as cliente_nome, l.nome as loja_nome, p.valor, p.vencimento FROM parcela p
                        RIGHT JOIN (
                            SELECT MAX(id) as id, id_divida FROM negociacao
                                GROUP BY id_divida
                        ) n
                            ON n.id=p.id_negociacao
                        LEFT JOIN  divida d
                        	ON d.id=n.id_divida
                        LEFT JOIN cliente c
                            ON c.id=d.id_cliente
                        LEFT JOIN loja l 
                            ON l.id=d.id_loja
                    WHERE p.vencimento < CURRENT_DATE()
                        AND p.pago=0 ";
        
        return $this->getEntityManager()->getConnection()->fetchAll($query);
        
        /*
        $query = $this->createQueryBuilder("P");
        
        $data = date("Y-m-d");
        
        $query->select("P, N, D, C, L")
                ->leftJoin("P.negociacao", "N")
                ->leftJoin("N.divida", "D")
                ->leftJoin("D.cliente", "C")
                ->leftJoin("D.loja", "L")
                ->andWhere($query->expr()->lt("P.vencimento", ":data"))
                ->andWhere($query->expr()->eq("P.pago", "0"))
                ->andWhere($query->expr()->eq("C.cobrancajudicial", "0"));
        $query->setParameter("data", $data, \PDO::PARAM_STR);
        return $query->getQuery()->getResult();
         *
         */
    }
    
    /**
     * 
     * @param int $idLoja
     * @return array
     */
    public function getParcelasFromLoja($idLoja, $mes, $ano)
    {
        $ultimoDia  = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
        
        $dataInicio = new \DateTime("{$ano}-{$mes}-01");
        $dataFim    = new \DateTime("{$ano}-{$mes}-{$ultimoDia}");
        $query      = $this->createQueryBuilder("P");
        
        $query->select("P, N, D, C")
                ->leftJoin("P.negociacao", "N")
                ->leftJoin("N.divida", "D")
                ->leftJoin("D.cliente", "C")
                ->andWhere($query->expr()->between("P.dataPagamento", ":dataInicio", ":dataFim"))
                ->andWhere($query->expr()->eq("P.pago", "1"))
                ->andWhere($query->expr()->eq("D.loja", ":idLoja"))
                ->orderBy("P.dataPagamento");
        $query->setParameter("idLoja", $idLoja, \PDO::PARAM_INT);
        $query->setParameter("dataInicio", $dataInicio->format("Y-m-d"));
        $query->setParameter("dataFim", $dataFim->format("Y-m-d"));
        
        return $query->getQuery()->getResult();
    }
    
    /**
     * 
     * @param int $idLoja
     * @return array
     */
    public function getValorTotalParcelasFromLoja($idLoja, $mes, $ano)
    {
        $ultimoDia  = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
        
        $dataInicio = new \DateTime("{$ano}-{$mes}-01");
        $dataFim    = new \DateTime("{$ano}-{$mes}-{$ultimoDia}");

        $query      = $this->createQueryBuilder("P");
        
        $query->select("SUM(P.valorPago)")
                ->leftJoin("P.negociacao", "N")
                ->leftJoin("N.divida", "D")
                ->andWhere($query->expr()->between("P.dataPagamento", ":dataInicio", ":dataFim"))
                ->andWhere($query->expr()->eq("P.pago", "1"))
                ->andWhere($query->expr()->eq("D.loja", ":idLoja"));
        $query->setParameter("idLoja", $idLoja, \PDO::PARAM_INT);
        $query->setParameter("dataInicio", $dataInicio->format("Y-m-d"));
        $query->setParameter("dataFim", $dataFim->format("Y-m-d"));
        return $query->getQuery()->getSingleScalarResult();
    }
    
    /**
     * 
     * @param int $idLoja
     * @return array
     */
    public function getRelatorioMensal($mes, $ano)
    {
        $ultimoDia  = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
        
        $dataInicio = new \DateTime("{$ano}-{$mes}-01");
        $dataFim    = new \DateTime("{$ano}-{$mes}-{$ultimoDia}");
        
        $query      = $this->createQueryBuilder("P");
        
        $query->select("L.nome")
                ->addSelect("SUM(CASE 
                                WHEN P.tipo = 's' 
                                        THEN P.valorPago
                                        ELSE 0
                                END)")
                ->addSelect("SUM(CASE 
                                WHEN P.tipo = 'l'
                                        THEN P.valorPago
                                        ELSE 0
                                END)")
                ->leftJoin("P.negociacao", "N")
                ->leftJoin("N.divida", "D")
                ->leftJoin("D.loja", "L")
                ->andWhere($query->expr()->between("P.dataPagamento", ":dataInicio", ":dataFim"))
                ->andWhere($query->expr()->eq("P.pago", "1"))
                //->andWhere($query->expr()->eq("P.tipo", ":tipo"))
                ->groupBy("L.id");
        $query->setParameter("dataInicio", $dataInicio->format("Y-m-d"));
        $query->setParameter("dataFim", $dataFim->format("Y-m-d"));
        //$query->setParameter("tipo", "S");
        
        return $query->getQuery()->getResult();
    }
    
    /**
     * 
     * @param int $idLoja
     * @return array
     */
    public function getParcelasMesAno($mes, $ano)
    {
        $ultimoDia  = cal_days_in_month(CAL_GREGORIAN, $mes, $ano);
        
        $dataInicio = new \DateTime("{$ano}-{$mes}-01");
        $dataFim    = new \DateTime("{$ano}-{$mes}-{$ultimoDia}");
        
        $query      = $this->createQueryBuilder("P");
        
        $query->select("P")
                ->leftJoin("P.negociacao", "N")
                ->andWhere($query->expr()->between("P.dataPagamento", ":dataInicio", ":dataFim"))
                ->andWhere($query->expr()->eq("P.pago", "1"));
        $query->setParameter("dataInicio", $dataInicio->format("Y-m-d"));
        $query->setParameter("dataFim", $dataFim->format("Y-m-d"));
        
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
    
    /**
     * Retonr ao ano Atual da PArcela
     * 
     * @param int $ano
     * @return int
     */
    public function getUltimoNumero($ano)
    {
        $query = "SELECT MAX(numero) FROM parcela WHERE YEAR(vencimento) = $ano";
        return (int) $this->getEntityManager()->getConnection()->fetchColumn($query);
    }
}
