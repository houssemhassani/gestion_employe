<?php

namespace App\Repository;

use App\Entity\AttendanceRecord;
use App\Entity\SalaryAdvance;
use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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
    private EntityManagerInterface $entityManager;
    private AttendanceRepository $attendanceRepository;
    private AttendanceRecordRepository $attendanceRecordRepository;

    public function __construct(AttendanceRepository $attendanceRepository,ManagerRegistry $registry,EntityManagerInterface $entityManager,UserRepository $userRepository,AttendanceRecordRepository $attendanceRecordRepository)
    {

        parent::__construct($registry, SalaryAdvance::class);
        $this->userRpository=$userRepository;
        $this->entityManager=$entityManager;
        $this->attendanceRecordRepository=$attendanceRecordRepository;
        $this->attendanceRepository=$attendanceRepository;
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
    /**
     * @return void Returns an array of SalaryAdvance objects
     */
    public function IncrementTotalOfAdvanceInSalary(User $user,$value){
         $attendanceRecord=new AttendanceRecord();


        $attendanceRecords=$user->getAttendancerecord();
         $totalpresence=0;
        foreach ($attendanceRecords as $item) {
            if ($item->getAttendancess() != null) {
                foreach ($item->getAttendancess() as $item1) {
                    if ($item1->isTypeOfAttendace()) {
                        $totalpresence += 1;
                    }
                }
            }
        }
        $totalSalary=($totalpresence * $user->getSalary());
        foreach ($attendanceRecords as $item){
            if($item->getUser()==$user){
                if($value<=$totalSalary){
                $attendanceRecord=$item;
                echo("8964a6546");
                $attendanceRecord->setTotalOfAdvanceSalary($attendanceRecord->getTotalOfAdvanceSalary()+$value);
                $this->entityManager->persist($attendanceRecord);
                $this->entityManager->flush();
                break;
            }
                else
                    break;

        }
            else
                break;

        }

    }
    public function maxAdvanceSalaryByEmployeIdAndMonth($id): ?float{
        $user=$this->userRpository->find($id);
        $nbr=$this->attendanceRepository->findAttendancesByEmployeId($id,8,2023)+1;
        echo($nbr);
        echo("\t salary ".$user->getSalary()."   ");
        $sal=$user->getSalary();
        //$totalSalary=$user->getSalary() * $nbr;
        $totalSalary=0;
        $attendanceRecord=new AttendanceRecord();

        $attendanceRecord=$this->attendanceRecordRepository->findOneBySomeField($id,8,2023);
        //dd($attendanceRecord);
        echo($attendanceRecord->getTotalOfAdvanceSalary());
        return ($sal * $nbr)- $attendanceRecord->getTotalOfAdvanceSalary();
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
