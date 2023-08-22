<?php

// src/Controller/ScoreController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\ScoreType;
use App\Service\EvaluationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/score')]
class ScoreController extends AbstractController
{
    private $evaluationService;

    public function __construct(EvaluationService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }

    /**
     * @Route("/evaluate/{employee}", name="evaluate_employee")
     */
    public function evaluateEmployee(Request $request, User $employee): Response
    {
        // Fetch the employee using $employeeId



        $form = $this->createForm(ScoreType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $scoreData = $form->getData();
            dd($employee);
            // Use the EvaluationService to evaluate the employee
            $this->evaluationService->evaluateEmployee($employee, $scoreData['scoreEvaluation'], $scoreData['commentEvaluation']);

            $this->addFlash('success', 'Employee evaluation saved successfully.');

            // Redirect to a success page or do something else
            return $this->redirectToRoute('success_route');
        }

        return $this->render('score/new.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee,
        ]);
    }
}
