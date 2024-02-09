<?php

namespace App\Repository;

use App\Entity\Quote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
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
     * @param array $searchResult
     * @return Quote[] Returns an array of Quote objects
     */
    public function findBySearch(array $searchResult): array
    {
        $searchResult = array_filter($searchResult, function ($value) {
            return !empty($value);
        });

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata(Quote::class, 'q');

        $sql = '
        WITH total_prices AS (
            SELECT q.id, SUM(qi.price_including_tax * qi.quantity) as total
            FROM quote q
            INNER JOIN quote_item qi ON q.id = qi.quote_id
            GROUP BY q.id
        )
        SELECT q.*, tp.total
        FROM quote q
        INNER JOIN total_prices tp ON q.id = tp.id
        WHERE 1 = 1';

        $params = [];
        if (!empty($searchResult)) {
            if (array_key_exists("status", $searchResult)) {
                $sql .= ' AND q.status_id IN (:status)';
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
                $sql .= ' AND q.created_at >= :minDate';
                $params['minDate'] = $searchResult["minDate"];
            }

            if (array_key_exists("maxDate", $searchResult)) {
                $sql .= ' AND q.created_at <= :maxDate';
                $params['maxDate'] = $searchResult["maxDate"];
            }
        }

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
        $query->setParameters($params);

        return $query->getResult();
    }
}
