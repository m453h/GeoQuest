<?php

namespace App\Repository\Location;

use App\Entity\Location\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
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
}
