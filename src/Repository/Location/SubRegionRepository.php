<?php

namespace App\Repository\Location;

use App\Entity\Location\SubRegion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
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

}
