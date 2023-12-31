<?php

namespace App\Repository\Data;

use App\Entity\Data\CountryFact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CountryFact>
 *
 * @method CountryFact|null find($id, $lockMode = null, $lockVersion = null)
 * @method CountryFact|null findOneBy(array $criteria, array $orderBy = null)
 * @method CountryFact[]    findAll()
 * @method CountryFact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryFactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CountryFact::class);
    }

    public function add(CountryFact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param array $options
     * @return QueryBuilder
     */
    public function getAll(array $options = []): QueryBuilder
    {
        $conn = $this->getEntityManager()->getConnection();
        $queryBuilder = new QueryBuilder($conn);
        $queryBuilder->select('f.id, 
        content, 
        is_user_created, 
        ct.description as content_type, 
        t.description AS fact_type')
            ->from('tbl_country_facts', 'f')
            ->join('f','cfg_fact_types','t','t.id=f.fact_type_id')
            ->join('f','cfg_content_types','ct','ct.id=f.content_type_id');
        $queryBuilder = $this->setFilterOptions($options, $queryBuilder);
        return $this->setSortOptions($options, $queryBuilder);
    }

    public function countAll(QueryBuilder $queryBuilder): \Closure
    {
        return function ($queryBuilder) {
            $queryBuilder->select('COUNT(DISTINCT f.id) AS total_results')
                ->setMaxResults(1)
                ->resetQueryPart('orderBy')
                ->resetQueryPart('groupBy');
        };
    }

    public function setFilterOptions($options, QueryBuilder $queryBuilder): QueryBuilder
    {
        if (!empty($options['country']))
        {
            return $queryBuilder->andwhere('f.country_id=:countryId')
                ->setParameter('countryId', $options['country']);
        }
        return $queryBuilder;
    }

    public function setSortOptions($options, QueryBuilder $queryBuilder): QueryBuilder
    {
        $options['sortType'] == 'desc' ? $sortType = 'desc' : $sortType = 'asc';
        return $queryBuilder->addOrderBy('f.id', 'desc');
    }

}
