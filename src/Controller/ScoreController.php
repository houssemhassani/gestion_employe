<?php

// src/Controller/ScoreController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\ScoreType;
use App\Entity\Score;
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
        $score=new Score();

        $form = $this->createForm(ScoreType::class,$score);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            // Use the EvaluationService to evaluate the employee
            $this->evaluationService->evaluateEmployee($employee,$score);

            $this->addFlash('success', 'Employee evaluation saved successfully.');

            // Redirect to a success page or do something else
            return $this->redirectToRoute('app_employe_index');
        }

        return $this->render('score/new.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee,
        ]);
    }
}
