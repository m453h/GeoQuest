<?php

namespace App\Repository\Location;

use App\Entity\Location\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Region>
 *
 * @method Region|null find($id, $lockMode = null, $lockVersion = null)
 * @method Region|null findOneBy(array $criteria, array $orderBy = null)
 * @method Region[]    findAll()
 * @method Region[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Region::class);
    }

    public function add(Region $entity, bool $flush = false): void
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
    public function removeAllRegions()
    {
        $conn = $this->getEntityManager()->getConnection();
        $conn->executeQuery("TRUNCATE TABLE cfg_regions CASCADE;");
        return true;
    }

    public function getAllRegionIds(): ArrayCollection
    {
        $regions = $this->findAll();
        $regionIds = new ArrayCollection();

        foreach ($regions as $region) {
            $regionIds->set($region->getName(), $region);
        }

        return $regionIds;
    }

}
