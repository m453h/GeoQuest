<?php

namespace App\Repository\Data;

use App\Entity\Data\CountryFact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception;
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

    /**
     * @param array $options
     * @return QueryBuilder
     */
    public function fetchAll(array $options = []): QueryBuilder
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

    /**
     * @param $factId
     * @return array[]
     * @throws Exception
     */
    public function getQuestionsForFactId($factId): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $queryBuilder = new QueryBuilder($conn);

        return $queryBuilder->select('f.id,
                                             content,
                                             question_prompt as prompt,
                                             ct.description as content_type,
                                             country_id as answer_id,
                                             api_field,
                                             c.name as answer_label,
                                             t.description AS fact_type')
            ->from('tbl_country_facts', 'f')
            ->join('f','cfg_fact_types','t','t.id=f.fact_type_id')
            ->join('f','cfg_content_types','ct','ct.id=f.content_type_id')
            ->join('f','cfg_countries','c','c.id=f.country_id')
            ->andWhere('fact_type_id=:id')
            ->setParameter('id',$factId)
            ->addOrderBy('RANDOM()')
            ->setMaxResults('10')
            ->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function getMultipleChoiceOptions($countryId): array|bool
    {
        $conn = $this->getEntityManager()->getConnection();
        $queryBuilder = new QueryBuilder($conn);
        return $queryBuilder->select('c.id, name')
            ->from('cfg_countries', 'c')
            ->andWhere('c.id<>:countryId')
            ->setParameter('countryId',$countryId)
            ->addOrderBy('RANDOM()')
            ->setMaxResults('4')
            ->fetchAllAssociative();
    }

    /**
     * @param $factId
     * @return array[]
     * @throws Exception
     */
    public function getCountryIdByFactId($factId): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $queryBuilder = new QueryBuilder($conn);
        return $queryBuilder->select('country_id as id, name as text')
            ->from('tbl_country_facts', 'f')
            ->join('f','cfg_countries','c','c.id=f.country_id')
            ->andWhere('f.id=:id')
            ->setParameter('id',$factId)
            ->fetchAssociative();
    }

    /**
     * @throws Exception
     */
    public function getTotals(){
        $conn = $this->getEntityManager()->getConnection();
        $queryBuilder = new QueryBuilder($conn);
        $data = $queryBuilder
            ->select('COUNT(u.id)')
            ->from('tbl_country_facts', 'u')
            ->fetchNumeric();
        return ($data[0]);
    }

}
