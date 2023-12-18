<?php

namespace App\Repository\Configuration;

use App\Entity\Configuration\FactType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
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


}
