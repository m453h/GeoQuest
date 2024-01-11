<?php

namespace App\Repository\Data;

use App\Entity\Data\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quiz>
 *
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    /**
     * @throws Exception
     */
    public function getTotals(){
        $conn = $this->getEntityManager()->getConnection();
        $queryBuilder = new QueryBuilder($conn);
        $data = $queryBuilder
            ->select('COUNT(u.id)')
            ->from('tbl_user_quizzes', 'u')
            ->fetchNumeric();

        return ($data[0]);
    }

    /**
     * @return array[]
     * @throws Exception
     */
    public function getCurrentLeaderBoards(): array
    {

        $conn = $this->getEntityManager()->getConnection();

        $queryBuilder = new QueryBuilder($conn);

        return $queryBuilder->select('u.id, first_name, middle_name, last_name, COUNT(is_correct) as number_of_correct_answers')
            ->from('tbl_user_quizzes', 'uq')
            ->join('uq','tbl_quiz_questions','qq','qq.quiz_id=uq.id')
            ->join('uq','tbl_user_accounts','u','u.id=uq.quiz_owner_id')
            ->andWhere('is_correct is true')
            ->addGroupBy('u.id')
            ->fetchAllAssociative();

    }

//    /**
//     * @return Quiz[] Returns an array of Quiz objects
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

//    public function findOneBySomeField($value): ?Quiz
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
