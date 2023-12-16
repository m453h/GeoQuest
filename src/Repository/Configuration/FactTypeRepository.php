<?php

namespace App\Repository\Configuration;

use App\Entity\Configuration\FactType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

//    /**
//     * @return FactType[] Returns an array of FactType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FactType
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
