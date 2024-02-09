<?php

namespace App\Repository;

use App\Config\InvoiceStatusEnum;
use App\Entity\Invoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invoice>
 *
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    /**
     * @param array $searchResult
     * @return Invoice[] Returns an array of Invoice objects
     */
    public function findBySearch(array $searchResult): array
    {
        $searchResult = array_filter($searchResult, function ($value) {
            return !empty($value);
        });

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Invoice::class, 'i');

        $sql = '
        WITH total_prices AS (
            SELECT i.id, SUM(ii.price_including_tax * ii.quantity) as total
            FROM invoice i
            INNER JOIN invoice_item ii ON i.id = ii.invoice_id
            GROUP BY i.id
        )
        SELECT i.*, tp.total
        FROM invoice i
        INNER JOIN total_prices tp ON i.id = tp.id
        WHERE 1 = 1';

        $params = [];
        if (!empty($searchResult)) {
            if (array_key_exists("status", $searchResult)) {
                $sql .= ' AND i.status_id IN (:status)';
                $params['status'] = $searchResult["status"];
            }

            if (array_key_exists("priceMin", $searchResult)) {
                $sql .= ' AND tp.total >= :priceMin';
                $params['priceMin'] = $searchResult["priceMin"];
            }

            if (array_key_exists("priceMax", $searchResult)) {
                $sql .= ' AND tp.total <= :priceMax';
                $params['priceMax'] = $searchResult["priceMax"];
            }

            if (array_key_exists("minDate", $searchResult)) {
                $sql .= ' AND i.created_at >= :minDate';
                $params['minDate'] = $searchResult["minDate"];
            }

            if (array_key_exists("maxDate", $searchResult)) {
                $sql .= ' AND i.created_at <= :maxDate';
                $params['maxDate'] = $searchResult["maxDate"];
            }
        }

        $sql .= ' ORDER BY i.created_at DESC';

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameters($params);

        return $query->getResult();
    }

    /**
     * Compte le nombre d'invoices en retard (- de 30 jours). Les conditions sont les suivantes :
     *
     * - status différent de "Annulé"
     * - (date d'échéance (dueAt) + 30 jours) > date actuelle
     * - la somme des paiements pour chaque invoice est inférieure aux prix de la facture sum(invoiceitems.priceIncludingTax * quantity)
     *
     * @return int Le nombre d'invoices en retard (- de 30 jours)
     */
    public function countLateInvoices1(): int
    {
        $qb = $this->createQueryBuilder('i');
        $qb->select('COUNT(i.id)')
            ->leftJoin('i.status', 's')
            ->leftJoin('i.payments', 'p')
            ->leftJoin('i.invoiceItems', 'ii')
            ->groupBy('i.id')
            ->having('SUM(p.amount) < SUM(ii.priceIncludingTax * ii.quantity)')
            ->andWhere('s.name != :cancelledStatus')
            ->setParameter('cancelledStatus', InvoiceStatusEnum::CANCELLED->value)
            ->andWhere('DATE_ADD(i.dueAt, 30, \'day\') > CURRENT_DATE()');

        return (int)$qb->getQuery()->getResult();
    }

    /**
     * Compte le nombre d'invoices en retard (+ de 30 jours). Les conditions sont les suivantes :
     *
     * - status différent de "Annulé"
     * - (date d'échéance (dueAt) + 30 jours) < date actuelle
     * - la somme des paiements pour chaque invoice est inférieure aux prix de la facture sum(invoiceitems.priceIncludingTax * quantity)
     *
     * @return int Le nombre d'invoices en retard (+ de 30 jours)
     */
    public function countLateInvoices2(): int
    {
        $qb = $this->createQueryBuilder('i');
        $qb->select('COUNT(i.id)')
            ->leftJoin('i.status', 's')
            ->leftJoin('i.payments', 'p')
            ->leftJoin('i.invoiceItems', 'ii')
            ->groupBy('i.id')
            ->having('SUM(p.amount) < SUM(ii.priceIncludingTax * ii.quantity)')
            ->andWhere('s.name != :cancelledStatus')
            ->setParameter('cancelledStatus', InvoiceStatusEnum::CANCELLED->value)
            ->andWhere('DATE_ADD(i.dueAt, 30, \'day\') < CURRENT_DATE()');

        return (int)$qb->getQuery()->getResult();
    }

    /**
     * Compte le nombre d'invoices non échues
     *
     * - status différent de "Annulé"
     * - date d'échéance (dueAt) < date actuelle
     * - la somme des paiements pour chaque invoice est inférieure aux prix de la facture sum(invoiceitems.priceIncludingTax * quantity)
     *
     * @return int Le nombre d'invoices non échues
     */
    public function countUnpaindInvoices(): int
    {
        $qb = $this->createQueryBuilder('i');
        $qb->select('COUNT(i.id)')
            ->leftJoin('i.status', 's')
            ->leftJoin('i.payments', 'p')
            ->leftJoin('i.invoiceItems', 'ii')
            ->groupBy('i.id')
            ->having('SUM(p.amount) < SUM(ii.priceIncludingTax * ii.quantity)')
            ->andWhere('s.name != :cancelledStatus')
            ->setParameter('cancelledStatus', 'Annulé')
            ->andWhere('i.dueAt < CURRENT_DATE()');

        return (int)$qb->getQuery()->getResult();
    }

}
