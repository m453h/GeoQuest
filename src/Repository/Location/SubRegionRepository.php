<?php

namespace App\Repository\Location;

use App\Entity\Location\SubRegion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SubRegion>
 *
 * @method SubRegion|null find($id, $lockMode = null, $lockVersion = null)
 * @method SubRegion|null findOneBy(array $criteria, array $orderBy = null)
 * @method SubRegion[]    findAll()
 * @method SubRegion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubRegionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SubRegion::class);
    }

    public function add(SubRegion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return true
     * @throws Exception
     */
    public function removeAll()
    {
        $conn = $this->getEntityManager()->getConnection();
        $conn->executeQuery("TRUNCATE TABLE cfg_sub_regions CASCADE;");
        return true;
    }

    public function getAllObjectArrayCollection(): ArrayCollection
    {
        $subRegions = $this->findAll();
        $arrayCollection = new ArrayCollection();

        foreach ($subRegions as $subRegion) {
            $arrayCollection->set($subRegion->getName(), $subRegion);
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
        $queryBuilder->select('sr.id, sr.name, r.name as region_name')
            ->from('cfg_sub_regions', 'sr')
            ->join('sr','cfg_regions','r','r.id=sr.region_id');
        $queryBuilder = $this->setFilterOptions($options, $queryBuilder);
        return $this->setSortOptions($options, $queryBuilder);
    }

    public function countAll(QueryBuilder $queryBuilder): \Closure
    {
        return function ($queryBuilder) {
            $queryBuilder->select('COUNT(DISTINCT sr.id) AS total_results')
                ->setMaxResults(1)
                ->resetQueryPart('orderBy')
                ->resetQueryPart('groupBy');
        };
    }

    public function setFilterOptions($options, QueryBuilder $queryBuilder): QueryBuilder
    {

        if (!empty($options['name']))
        {
            return $queryBuilder->andwhere('lower(sr.name) LIKE lower(:name)')
                ->setParameter('name', '%' . $options['name'] . '%');
        }

        if (!empty($options['region']))
        {
            return $queryBuilder->andwhere('lower(r.name) LIKE lower(:name)')
                ->setParameter('name', '%' . $options['region'] . '%');
        }

        return $queryBuilder;
    }

    public function setSortOptions($options, QueryBuilder $queryBuilder): QueryBuilder
    {

        $options['sortType'] == 'desc' ? $sortType = 'desc' : $sortType = 'asc';

        if ($options['sortBy'] === 'name')
        {
            return $queryBuilder->addOrderBy('sr.name', $sortType);
        }

        if ($options['sortBy'] === 'region')
        {
            return $queryBuilder->addOrderBy('r.name', $sortType);
        }

        return $queryBuilder->addOrderBy('sr.id', 'desc');
    }

}
