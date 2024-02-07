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

    //Compte les factures en retard (- de 30 jours)
    // public function countLateInvoices1(): int
    // {
    //     return $this->createQueryBuilder('i')
    //     ->select('COUNT(i.id)')
    //     ->innerJoin('i.payments', 'p')
    //     ->innerJoin('i.status', 's')
    //     ->where('i.dueAt < :thirtyDaysAgo') 
    //     ->andWhere('s.name != :cancelled OR s.name IS NULL') // Statut différent de "Annulé" ou nul
    //     ->andWhere(
    //         'COALESCE(SUM(p.amount), 0) < i.totalIncludingTax'
    //     )
    //     ->setParameter('thirtyDaysAgo', new \DateTime('-30 days'))
    //     ->setParameter('cancelled', 'Annulé')
    //     ->getQuery()
    //     ->getSingleScalarResult();
    // }


}
