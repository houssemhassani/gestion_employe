<?php
namespace App\Service;
use App\Repository\UserRepository;
use App\Repository\AttendanceRecordRepository;
use App\Repository\AttendaceRepository;
use App\Entity\User;
use App\Entity\Attendance;
use App\Entity\AttendanceRecord;


 class AttendanceService {
    private UserRepository $userRepository ;
    private AttendaceRepository $attendanceRepository;
    private AttendanceRecordRepository $attendanceRecordRepository;

    public function getAllAttendanceAndByUser(int $id,int $year,int $month):any
    {
        $user=$this->userRepository->find($id);
        if (!$user instanceof User) {
            throw $this->createNotFoundException('User not found.');
        }

        // Récupérer l'AttendanceRecord pour l'année 2023 et le mois 08
        $entityManager = $this->getDoctrine()->getManager();
        $attendanceRecord = $entityManager->getRepository(AttendanceRecord::class)
            ->findOneBy([
                'user' => $user,
                'year' => 2023,
                'month' => 8
            ]);

        // Vérifier si l'AttendanceRecord existe
        if (!$attendanceRecord instanceof AttendanceRecord) {
            throw $this->createNotFoundException('Attendance record not found.');
        }

        // Récupérer la liste des attendances associées à cet AttendanceRecord
        $attendances = $attendanceRecord->getAttendances();

        // Faites ce que vous voulez avec la liste des attendances

        return  $attendances;
        

    }
}