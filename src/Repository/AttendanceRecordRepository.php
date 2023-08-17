<?php

namespace App\Repository;

use App\Entity\AttendanceRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\UserRepository;

/**
 * @extends ServiceEntityRepository<AttendanceRecord>
 *
 * @method AttendanceRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method AttendanceRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method AttendanceRecord[]    findAll()
 * @method AttendanceRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttendanceRecordRepository extends ServiceEntityRepository
{

    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AttendanceRecord::class);
    }

//    /**
//     * @return AttendanceRecord[] Returns an array of AttendanceRecord objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AttendanceRecord
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


}
