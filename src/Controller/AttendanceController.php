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
use Symfony\Component\Security\Core\Security;

#[Route('/attendance')]
class AttendanceController extends AbstractController
{
    #[Route('/', name: 'app_attendance_index', methods: ['GET'])]
    public function index(Security $security,AttendanceRepository $attendanceRepository): Response
    {
        if($security->getUser()->getRoles()==["GRH"]){

        return $this->render('attendance/index.html.twig', [
            'attendances' => $attendanceRepository->findAll(),
        ]);
    }
    else
        return $this->render('error_modal.html.twig');;
    }
    #[Route('/myattendances', name: 'app_attendance_current_date_and_user', methods: ['GET'])]
    public function allAttendancesByCurrentUserAndCurrentMonth(Security $security,AttendanceRepository $attendanceRepository): Response
    {
        $user=$security->getUser();
        $currentDate = new \DateTime();
        $currentMonth = $currentDate->format('n');
        $list=$attendanceRepository->getAllAttendancesByCurrentUserAndCurrentMonth($user,$currentDate);

            return $this->render('attendance/app_attendance_current_date_and_user.html.twig', [
                'attendances' => $list
            ]);
    }

    #[Route('/present/{id}', name: 'app_attendance_present', methods: ['GET', 'POST'])]
    public function present(Security $security,Request $request, EntityManagerInterface $entityManager,UserRepository $userRepository,$id): Response
    {
        if($security->getUser()->getRoles()==["GRH"]) {
            $user = $userRepository->findEmployeById($id);
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
            } else

                return $this->renderForm('attendance/new.html.twig', [
                    'attendance' => $attendance,
                    'form' => $form,
                ]);
        }
        else
            return $this->render('error_modal.html.twig');;
    }

    #[Route('/absent/{id}', name: 'app_attendance_absent', methods: ['GET', 'POST'])]
    public function absent(Security $security,Request $request, EntityManagerInterface $entityManager,UserRepository $userRepository,$id): Response
    {
        if($security->getUser()->getRoles()==["GRH"]) {
            $user = new User();
            $user = $userRepository->findOneBy(['id' => $id]);
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
            } else

                return $this->renderForm('attendance/new.html.twig', [
                    'attendance' => $attendance,
                    'form' => $form,
                ]);
        }else
            return $this->render('error_modal.html.twig');;
    }

    #[Route('/{id}', name: 'app_attendance_show', methods: ['GET'])]
    public function show(Attendance $attendance): Response
    {
        return $this->render('attendance/show.html.twig', [
            'attendance' => $attendance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_attendance_edit', methods: ['GET', 'POST'])]
    public function edit(Security $security,Request $request, Attendance $attendance, EntityManagerInterface $entityManager): Response
    {
        if ($security->getUser()->getRoles() == ["GRH"]) {
            $form = $this->createForm(AttendanceType::class, $attendance);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();

                return $this->redirectToRoute('app_attendance_index', [], Response::HTTP_SEE_OTHER);
            } else

                return $this->renderForm('attendance/edit.html.twig', [
                    'attendance' => $attendance,
                    'form' => $form,
                ]);
        } else
            return $this->render('error_modal.html.twig');
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
