<?php

namespace App\Controller;

use App\Entity\Attendance;
use App\Entity\User;
use App\Form\AttendanceType;
use App\Repository\AttendanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/attendance')]
class AttendanceController extends AbstractController
{
    #[Route('/', name: 'app_attendance_index', methods: ['GET'])]
    public function index(AttendanceRepository $attendanceRepository): Response
    {
        return $this->render('attendance/index.html.twig', [
            'attendances' => $attendanceRepository->findAll(),
        ]);
    }

    #[Route('/present/{id}', name: 'app_attendance_present', methods: ['GET', 'POST'])]
    public function present(Request $request, EntityManagerInterface $entityManager,UserRepository $userRepository,$id): Response
    {
        echo($id);
        $user=$userRepository->findEmployeById($id);
        echo($user->getNom());
        $attendance = new Attendance();

        $attendance->setTypeOfAttendace(true);
        $attendance->setCreatedAt(new \DateTime());
        $form = $this->createForm(AttendanceType::class, $attendance);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $attendance->setUser($user);
            $entityManager->persist($attendance);
            $entityManager->flush();

            return $this->redirectToRoute('app_attendance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('attendance/new.html.twig', [
            'attendance' => $attendance,
            'form' => $form,
        ]);
    }

    #[Route('/absent/{id}', name: 'app_attendance_absent', methods: ['GET', 'POST'])]
    public function absent(Request $request, EntityManagerInterface $entityManager,UserRepository $userRepository,$id): Response
    {
        $user=new User();
        $user=$userRepository->findOneBy(['id'=>$id]);
        $attendance = new Attendance();
        $attendance->setUser($user);
        $attendance->setTypeOfAttendace(false);
        $attendance->setCreatedAt(new \DateTime());
        $form = $this->createForm(AttendanceType::class, $attendance);
        $form->handleRequest($request);
       

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($attendance);
            $entityManager->flush();

            return $this->redirectToRoute('app_attendance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('attendance/new.html.twig', [
            'attendance' => $attendance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_attendance_show', methods: ['GET'])]
    public function show(Attendance $attendance): Response
    {
        return $this->render('attendance/show.html.twig', [
            'attendance' => $attendance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_attendance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Attendance $attendance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AttendanceType::class, $attendance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_attendance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('attendance/edit.html.twig', [
            'attendance' => $attendance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_attendance_delete', methods: ['POST'])]
    public function delete(Request $request, Attendance $attendance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$attendance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($attendance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_attendance_index', [], Response::HTTP_SEE_OTHER);
    }
}
