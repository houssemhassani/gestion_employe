<?php

namespace App\Controller;

use App\Entity\SalaryAdvance;
use App\Entity\User;
use App\Form\SalaryAdvanceType;
use App\Repository\SalaryAdvanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/salary/advance')]
class SalaryAdvanceController extends AbstractController
{
    #[Route('/{userId}', name: 'app_salary_advance_index', methods: ['GET'])]
    public function index($userId,SalaryAdvanceRepository $salaryAdvanceRepository): Response
    {
        return $this->render('salary_advance/index.html.twig', [
            'salary_advances' => $salaryAdvanceRepository->findSalaryAdvances($userId),
        ]);
    }

    #[Route('/new/{user}', name: 'app_salary_advance_new', methods: ['GET', 'POST'])]
    public function new(User $user,Request $request, EntityManagerInterface $entityManager,SalaryAdvanceRepository $salaryAdvanceRepository): Response
    {
       // dd($user->getId());
        $userId=$user->getId();
        $salaryAdvance = new SalaryAdvance();
        $form = $this->createForm(SalaryAdvanceType::class, $salaryAdvance);
        $salaryAdvance->setEmploye($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($salaryAdvance->getAmount()<=$salaryAdvanceRepository->maxAdvanceSalaryByEmployeIdAndMonth($userId))
            {
                $salaryAdvanceRepository->IncrementTotalOfAdvanceInSalary($user,$salaryAdvance->getAmount());
                $entityManager->persist($salaryAdvance);
                $entityManager->flush();
            }



            return $this->redirectToRoute('app_salary_advance_index', ['userId'=>$userId], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('salary_advance/new.html.twig', [
            'salary_advance' => $salaryAdvance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salary_advance_show', methods: ['GET'])]
    public function show(SalaryAdvance $salaryAdvance): Response
    {
        return $this->render('salary_advance/show.html.twig', [
            'salary_advance' => $salaryAdvance,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_salary_advance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SalaryAdvance $salaryAdvance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SalaryAdvanceType::class, $salaryAdvance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_salary_advance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('salary_advance/edit.html.twig', [
            'salary_advance' => $salaryAdvance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_salary_advance_delete', methods: ['POST'])]
    public function delete(Request $request, SalaryAdvance $salaryAdvance, EntityManagerInterface $entityManager): Response
    {
        $user=$salaryAdvance->getEmploye();
        if ($this->isCsrfTokenValid('delete'.$salaryAdvance->getId(), $request->request->get('_token'))) {
            $entityManager->remove($salaryAdvance);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_salary_advance_index', ['userId'=>$user->getId()], Response::HTTP_SEE_OTHER);
    }
}
