<?php

namespace App\Controller;

use App\Entity\AttendanceRecord;
use App\Entity\Attendance;
use App\Form\AttendanceRecordType;
use App\Repository\UserRepository;
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

    #[Route('/getAllAttendanceByEmployee/{id}', name: 'app_all_attendance_record_show', methods: ['GET'])]
    public function getAllAttendanceByEmploye(User $user, UserRepository $userRepository, PdfService $pdf)
    {

        $attendanceRecord = new AttendanceRecord();
        $user = $userRepository->find($user->getId());
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
        $attendances = $attendanceRecord->getAttendancess();
        $html = $this->render('attendance_record/index.html.twig', ['attendances' => $attendances, 'employe' => $user],);
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
