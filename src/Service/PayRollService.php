<?php
namespace App\Service;
use App\Repository\UserRepository;
use App\Repository\AttendanceRecordRepository;
use App\Repository\AttendanceRepository;
use App\Repository\PayRollRepository;

use App\Entity\User;
use App\Entity\Attendance;
use App\Entity\AttendanceRecord;


 class PayRollService {
    private UserRepository $userRepository ;
    private AttendanceRepository $attendanceRepository;
    private AttendanceRecordRepository $attendanceRecordRepository;

    public function getEmploye(int $id){
        return $this->userRepository->find($id);
    }
    public function getPayRollByEmployeId($employeeId,PayRollRepository $payRollRepository)
    {
        return $payRollRepository->findByEmployeeId($employeeId);
    }

 }