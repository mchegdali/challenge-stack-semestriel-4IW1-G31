<?php

namespace App\Repository;

use App\Entity\Quote;
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
     * @return Quote[] Returns an array of Quote objects
     */
    public function findBySearch(array $criteria): array
    {
        $qb = $this->createQueryBuilder('q')
            ->leftJoin('q.customer', 'c')
            ->leftJoin('q.status', 's');

        if (isset($criteria['text']) && !empty($criteria['text'])) {
            $qb
                ->andWhere(
                    $qb->expr()->orX(
                        $qb->expr()->like("c.customer_name", ":text"),
                        $qb->expr()->like("q.quote_number", ":text")
                    )
                )
                ->setParameter('text', '%' . $criteria['text'] . '%');
        }

        if (isset($criteria['status']) && !empty($criteria['status'])) {
            $qb
                ->andWhere(
                    $qb->expr()->in("s.id", ":status")
                )
                ->setParameter('status', $criteria['status']);
        }


        return $qb->getQuery()->getResult();
    }
}
