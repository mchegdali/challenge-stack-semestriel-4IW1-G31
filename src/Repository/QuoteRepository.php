<?php

namespace App\Repository;

use App\Entity\Quote;
use App\Entity\QuoteStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quote>
 *
 * @method Quote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quote[]    findAll()
 * @method Quote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quote::class);
    }

    /**
     * @param int[] $statuses
     * @return Quote[] Returns an array of Quote objects
     */
    public function findByStatuses(array $statuses): array
    {
        dump("findByStatuses statuses", $statuses);
        $qb = $this->createQueryBuilder('q');

        if (!empty($statuses)) {
            $qb
                ->andWhere(
                    $qb->expr()->in("q.status", $statuses)
                );
        }

        dump("findByStatuses qb", $qb->getQuery()->getSQL());



        return $qb->getQuery()->getResult();
    }
}
