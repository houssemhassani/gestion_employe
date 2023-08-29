<?php

namespace App\Repository;

use App\Entity\Attendance;
use App\Entity\User;
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
    public function getAllAttendancesByCurrentUserAndCurrentMonth(User $currentUser, \DateTimeInterface $date): array
    {
        $startOfMonth = clone $date;
        $startOfMonth->modify('first day of this month');
        $endOfMonth = clone $date;
        $endOfMonth->modify('last day of this month');

        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.user = :currentUser')
            ->setParameter('currentUser', $currentUser)
            ->andWhere('a.CreatedAt BETWEEN :startOfMonth AND :endOfMonth')
            ->setParameter('startOfMonth', $startOfMonth)
            ->setParameter('endOfMonth', $endOfMonth)
            ->orderBy('a.CreatedAt', 'DESC'); // You can order the attendances as needed

        return $qb->getQuery()->getResult();
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
