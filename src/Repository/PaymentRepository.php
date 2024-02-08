<?php

namespace App\Repository;

use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payment>
 *
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    /**
     * @param array $searchResult
     * @return Payment[] Returns an array of Quote objects
     */
    public function findBySearch(array $searchResult): array
    {
        $searchResult = array_filter($searchResult, function ($value) {
            return !empty($value);
        });

        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->leftJoin('p.customer', 'c')
            ->leftJoin('p.invoice', 'i')
            ->addSelect('c', 's', 'i');

        return $qb->getQuery()->getResult();
    }

    //Récupère la somme des paiements liés à l'entreprise de l'utilisateur connecté des 12 derniers mois ou du mois en cours
    public function findTotalPaymentsForCompany($companyId, $period)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('SUM(p.amount) as totalPayments')
            ->join('p.invoice', 'i')
            ->where('i.company = :companyId')
            ->setParameter('companyId', $companyId);

        $currentDate = new \DateTime();
        switch ($period) {
            case 'last_12_months':
                $date = (new \DateTime())->modify('-12 months');
                $qb->andWhere('p.paidAt >= :date')
                ->setParameter('date', $date);
                break;
            case 'current_month':
                $startOfMonth = $currentDate->modify('first day of this month')->setTime(0, 0);
                $qb->andWhere('p.paidAt > :startOfMonth')
                ->setParameter('startOfMonth', $startOfMonth);
                break;
        }
        return $qb->getQuery()->getSingleScalarResult();
    }
}
