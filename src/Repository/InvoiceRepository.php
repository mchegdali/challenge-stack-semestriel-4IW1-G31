<?php

namespace App\Repository;

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

//    /**
//     * @return Invoice[] Returns an array of Invoice objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Invoice
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    /**
     * @param array $searchResult
     * @return Invoice[] Returns an array of Quote objects
     */
    public function findBySearch(array $searchResult): array
    {
        $searchResult = array_filter($searchResult, function ($value) {
            return !empty($value);
        });

        $qb = $this->createQueryBuilder('i');
        $qb->innerJoin('i.invoiceItems', 'ii');
        $qb->addSelect('ii');

        if (!empty($searchResult)) {
            if (array_key_exists("status", $searchResult)) {
                $qb->andWhere('i.status IN (:status)');
                $qb->setParameter('status', $searchResult["status"]);
            }

            if (array_key_exists("priceMin", $searchResult)) {
                $qb->andWhere('ii.priceIncludingTax * ii.quantity >= :priceMin');
                $qb->setParameter('priceMin', $searchResult["priceMin"]);
            }

            if (array_key_exists("priceMax", $searchResult)) {
                $qb->andWhere('ii.priceIncludingTax * ii.quantity <= :priceMax');
                $qb->setParameter('priceMax', $searchResult["priceMax"]);
            }
        }
        return $qb->getQuery()->getResult();
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
            ->setParameter('cancelledStatus', 'Annulé')
            ->andWhere('DATE_ADD(i.dueAt, 30, \'day\') > CURRENT_DATE()');

        return (int) $qb->getQuery()->getResult();
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
            ->setParameter('cancelledStatus', 'Annulé')
            ->andWhere('DATE_ADD(i.dueAt, 30, \'day\') < CURRENT_DATE()');

        return (int) $qb->getQuery()->getResult();
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

        return (int) $qb->getQuery()->getResult();
    }

}
