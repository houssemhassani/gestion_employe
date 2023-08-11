<?php

namespace App\Controller;

use App\Entity\AttendanceRecord;
use App\Entity\Attendance;
use App\Form\AttendanceRecordType;
use App\Service\AttendanceService;
use App\Service\PdfService;
use App\Entity\User;

use App\Repository\AttendanceRecordRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/attendanceRecord')]
class AttendanceRecordController extends AbstractController
{
    #[Route('/', name: 'app_attendance_record_index', methods: ['GET'])]
    public function index(AttendanceRecordRepository $attendanceRecordRepository): Response
    {
        return $this->render('attendance_record/index.html.twig', [
            'attendance_records' => $attendanceRecordRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_attendance_record_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $attendanceRecord = new AttendanceRecord();
        $form = $this->createForm(AttendanceRecordType::class, $attendanceRecord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($attendanceRecord);
            $entityManager->flush();

            return $this->redirectToRoute('app_attendance_record_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('attendance_record/new.html.twig', [
            'attendance_record' => $attendanceRecord,
            'form' => $form,
        ]);
    }

    #[Route('/getAllAttendanceByEmploye/{id}', name: 'app_all_attendance_record_show', methods: ['GET'])]
    public function getAllAttendanceByEmploye(User $user,PdfService $pdf,AttendanceService $attendanceRecordService): Response
    {
        $attendanceRecord=new AttendanceRecord();
        $attendanceRecord->setUser($user);
        $attendanceRecord->addAttendance($attendanceRecordService->getAllAttendanceAndByUser(1,2023,8));
        $html=$this->render('attendance_record/index.html.twig',['attendance_record'=>$attendanceRecord,'user'=>$user],);
        $pdf->showPdFile($html);
        /* return $this->render('attendace_record/index.html.twig', [
            'user' => $user,
        ]); */
    }


    #[Route('/{id}', name: 'app_attendance_record_show', methods: ['GET'])]
    public function show(AttendanceRecord $attendanceRecord): Response
    {
        return $this->render('attendance_record/show.html.twig', [
            'attendance_record' => $attendanceRecord,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_attendance_record_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AttendanceRecord $attendanceRecord, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AttendanceRecordType::class, $attendanceRecord);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_attendance_record_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('attendance_record/edit.html.twig', [
            'attendance_record' => $attendanceRecord,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_attendance_record_delete', methods: ['POST'])]
    public function delete(Request $request, AttendanceRecord $attendanceRecord, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$attendanceRecord->getId(), $request->request->get('_token'))) {
            $entityManager->remove($attendanceRecord);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_attendance_record_index', [], Response::HTTP_SEE_OTHER);
    }
}
