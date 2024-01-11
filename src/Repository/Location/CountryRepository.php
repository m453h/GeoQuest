<?php

namespace App\Repository\Location;

use App\Entity\Location\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Country>
 *
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    /**
     * @return true
     * @throws Exception
     */
    public function removeAll()
    {
        $conn = $this->getEntityManager()->getConnection();
        $conn->executeQuery("TRUNCATE TABLE cfg_countries CASCADE;");
        return true;
    }

    public function add(Country $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getOrderedCountryList(){

        return $this->createQueryBuilder('country')
            ->orderBy('country.name','ASC')
            ->where('country.isFetched is NULL')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param array $options
     * @return QueryBuilder
     */
    public function getAll(array $options = []): QueryBuilder
    {

        $conn = $this->getEntityManager()->getConnection();

        $queryBuilder = new QueryBuilder($conn);
        $queryBuilder->select('c.id, c.name, sr.name as sub_region, r.name as region, is_fetched')
            ->from('cfg_countries', 'c')
            ->join('c','cfg_sub_regions','sr','sr.id=c.sub_region_id')
            ->join('sr','cfg_regions','r','r.id=sr.region_id');
        $queryBuilder = $this->setFilterOptions($options, $queryBuilder);
        return $this->setSortOptions($options, $queryBuilder);
    }

    public function countAll(QueryBuilder $queryBuilder): \Closure
    {
        return function ($queryBuilder) {
            $queryBuilder->select('COUNT(DISTINCT c.id) AS total_results')
                ->setMaxResults(1)
                ->resetQueryPart('orderBy')
                ->resetQueryPart('groupBy');
        };
    }

    public function setFilterOptions($options, QueryBuilder $queryBuilder): QueryBuilder
    {
        if (!empty($options['name']))
        {
            return $queryBuilder->andwhere('lower(c.name) LIKE lower(:name)')
                ->setParameter('name', '%' . $options['name'] . '%');
        }

        if (!empty($options['region']))
        {
            return $queryBuilder->andwhere('lower(r.name) LIKE lower(:name)')
                ->setParameter('name', '%' . $options['region'] . '%');
        }

        if (!empty($options['sub-region']))
        {
            return $queryBuilder->andwhere('lower(sr.name) LIKE lower(:name)')
                ->setParameter('name', '%' . $options['sub-region'] . '%');
        }

        return $queryBuilder;
    }

    public function setSortOptions($options, QueryBuilder $queryBuilder): QueryBuilder
    {
        $options['sortType'] == 'desc' ? $sortType = 'desc' : $sortType = 'asc';

        if ($options['sortBy'] === 'name')
        {
            return $queryBuilder->addOrderBy('c.name', $sortType);
        }

        if ($options['sortBy'] === 'region')
        {
            return $queryBuilder->addOrderBy('r.name', $sortType);
        }

        if ($options['sortBy'] === 'sub-region')
        {
            return $queryBuilder->addOrderBy('sr.name', $sortType);
        }

        return $queryBuilder->addOrderBy('c.id', 'desc');
    }

    /**
     * @throws Exception
     */
    public function getAllFacts($countryId): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $queryBuilder = new QueryBuilder($conn);
        return $queryBuilder->select('content as value, ct.description as type, t.description AS label')
            ->from('tbl_country_facts', 'f')
            ->join('f','cfg_fact_types','t','t.id=f.fact_type_id')
            ->join('f','cfg_content_types','ct','ct.id=f.content_type_id')
            ->andWhere('f.country_id=:countryId')
            ->setParameter('countryId',$countryId)
            ->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function getTotals(){
        $conn = $this->getEntityManager()->getConnection();
        $queryBuilder = new QueryBuilder($conn);
        $data = $queryBuilder
            ->select('COUNT(u.id)')
            ->from('cfg_countries', 'u')
            ->fetchNumeric();
        return ($data[0]);
    }
}
