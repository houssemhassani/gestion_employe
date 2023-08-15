<?php

namespace App\Repository;

use App\Entity\PayRoll;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PayRoll>
 *
 * @method PayRoll|null find($id, $lockMode = null, $lockVersion = null)
 * @method PayRoll|null findOneBy(array $criteria, array $orderBy = null)
 * @method PayRoll[]    findAll()
 * @method PayRoll[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PayRollRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PayRoll::class);
    }

    public function findByEmployeeId(int $employeeId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.employee = :employeeId')
            ->setParameter('employeeId', $employeeId)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return PayRoll[] Returns an array of PayRoll objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PayRoll
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
