<?php

namespace App\Repository\Configuration;

use App\Entity\Configuration\FactType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FactType>
 *
 * @method FactType|null find($id, $lockMode = null, $lockVersion = null)
 * @method FactType|null findOneBy(array $criteria, array $orderBy = null)
 * @method FactType[]    findAll()
 * @method FactType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FactType::class);
    }

    public function getAllObjectArrayCollection(): ArrayCollection
    {
        $objects = $this->findAll();
        $arrayCollection = new ArrayCollection();

        foreach ($objects as $object) {
            $arrayCollection->set($object->getApiField(), $object);
        }
        return $arrayCollection;
    }

    /**
     * @param array $options
     * @return QueryBuilder
     */
    public function getAll(array $options = []): QueryBuilder
    {

        $conn = $this->getEntityManager()->getConnection();

        $queryBuilder = new QueryBuilder($conn);
        $queryBuilder->select('id,
                               description,
                               api_field,
                               question_prompt')
            ->from('cfg_fact_types', 'p');
        $queryBuilder = $this->setFilterOptions($options, $queryBuilder);
        return $this->setSortOptions($options, $queryBuilder);
    }

    public function countAll(QueryBuilder $queryBuilder): \Closure
    {
        return function ($queryBuilder) {
            $queryBuilder->select('COUNT(DISTINCT id) AS total_results')
                ->setMaxResults(1)
                ->resetQueryPart('orderBy')
                ->resetQueryPart('groupBy');
        };
    }

    public function setFilterOptions($options, QueryBuilder $queryBuilder): QueryBuilder
    {
        if (!empty($options['description']))
        {
            return $queryBuilder->andwhere('lower(description) LIKE lower(:description)')
                ->setParameter('description', '%' . $options['description'] . '%');
        }
        return $queryBuilder;
    }

    public function setSortOptions($options, QueryBuilder $queryBuilder): QueryBuilder
    {
        $options['sortType'] == 'desc' ? $sortType = 'desc' : $sortType = 'asc';

        if ($options['sortBy'] === 'description')
        {
            return $queryBuilder->addOrderBy('description', $sortType);
        }

        return $queryBuilder->addOrderBy('id', 'desc');
    }
}
