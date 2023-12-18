<?php

namespace App\Repository\Data;

use App\Entity\Data\CountryFact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
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

}
