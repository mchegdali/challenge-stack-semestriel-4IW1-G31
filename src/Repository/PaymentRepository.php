<?php

namespace App\Repository;

use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
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
            ->leftJoin('p.status', 's')
            ->leftJoin('p.invoice', 'i')
            ->addSelect('c', 's', 'i');

        return $qb->getQuery()->getResult();
    }
}
