<?php

namespace App\Repository\Data;

use App\Entity\Data\QuizQuestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuizQuestion>
 *
 * @method QuizQuestion|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizQuestion|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizQuestion[]    findAll()
 * @method QuizQuestion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizQuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuizQuestion::class);
    }

//    /**
//     * @return QuizQuestion[] Returns an array of QuizQuestion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?QuizQuestion
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
