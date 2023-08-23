<?php

namespace App\Repository;

use App\Entity\Attendance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Attendance>
 *
 * @method Attendance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attendance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attendance[]    findAll()
 * @method Attendance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttendanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attendance::class);
    }

    /**
     * @return Attendance[] Returns an array of Attendance objects
     */
    public function findAttendancesByEmployeId($value,$month,$year):?int
    {
       $list= $this->createQueryBuilder('a')
           ->select('COUNT(a.id)')
            ->where('a.user = :val')
            ->andWhere('a.CreatedAt=:month')
            ->andWhere('a.CreatedAt= :year')
            ->andWhere('a.typeOfAttendace=:bool')
            ->setParameter('val', $value)
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('bool', true)
            ->setMaxResults(31)
            ->getQuery()
            ->getResult();
        return count($list);

    }

    public function findOneBySomeField($value): ?Attendance
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
