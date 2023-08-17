<?php

namespace App\Repository;

use App\Entity\SalaryAdvance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SalaryAdvance>
 *
 * @method SalaryAdvance|null find($id, $lockMode = null, $lockVersion = null)
 * @method SalaryAdvance|null findOneBy(array $criteria, array $orderBy = null)
 * @method SalaryAdvance[]    findAll()
 * @method SalaryAdvance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SalaryAdvanceRepository extends ServiceEntityRepository
{
    private UserRepository $userRpository;
    public function __construct(ManagerRegistry $registry,UserRepository $userRepository)
    {
        $this->userRpository=$userRepository;
        parent::__construct($registry, SalaryAdvance::class);
    }

    /**
     * @return SalaryAdvance[] Returns an array of SalaryAdvance objects
     */
    public function findSalaryAdvances($user): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.employe = :val')
            ->setParameter('val', $user)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?SalaryAdvance
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
