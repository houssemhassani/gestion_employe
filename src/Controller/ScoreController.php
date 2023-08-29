<?php

// src/Controller/ScoreController.php

namespace App\Controller;

use App\Entity\User;
use App\Form\ScoreType;
use App\Entity\Score;
use App\Repository\ScoreRepository;
use App\Service\EvaluationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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
    public function evaluateEmployee(Security $security, Request $request, User $employee): Response
    {
        if ($security->getUser()->getRoles() == ["GRH"]) {
            // Fetch the employee using $employeeId
            $score = new Score();

            $form = $this->createForm(ScoreType::class, $score);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {


                // Use the EvaluationService to evaluate the employee
                $this->evaluationService->evaluateEmployee($employee, $score);

                $this->addFlash('success', 'Employee evaluation saved successfully.');

                // Redirect to a success page or do something else
                return $this->redirectToRoute('app_employe_index');
            } else

                return $this->render('score/new.html.twig', [
                    'form' => $form->createView(),
                    'employee' => $employee,
                ]);
        }
        return $this->render('error_modal.html.twig');
    }

    #[Route('/employee/{employee}/scores', name: 'user_scores')]
    public function getUserScores(Security $security, User $employee): Response
    {
        // Retrieve the user by ID
        ;
        if ($security->getUser()->getRoles()) {
            if (!$employee) {
                throw $this->createNotFoundException('User not found');
            }

            // Get the scores for the user
            $scores = $this->evaluationService->getScoresByUserId($employee->getId());

            return $this->render('score/index.html.twig', [
                'employee' => $employee,
                'scores' => $scores,
            ]);
        } else
            return $this->render('error_modal.html.twig');
    }

    #[Route('/statistics/scores/{userId}', name: 'user_scores_statistics')]
    public function userScoresStatistics(Security $security, int $userId, ScoreRepository $scoreRepository): Response
    {
        if ($security->getUser()->getRoles() == ["GRH"] || $security->getUser()->getRoles() == ["EMPLOYE"]) {
            $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

            if (!$user) {
                throw $this->createNotFoundException('User not found');
            }

            // Get scores for the user
            $scores = $this->evaluationService->getScoresByUserId($userId);

            // Calculate statistics
            $statistics = [];
            foreach ($scores as $score) {
                $createdAt = $score->getCreatedAt();
                $monthYear = $createdAt->format('Y-m');

                if (!isset($statistics[$monthYear])) {
                    $statistics[$monthYear] = [
                        'totalScores' => 0,
                        'averageScore' => 0,
                    ];
                }

                $statistics[$monthYear]['totalScores']++;
                $statistics[$monthYear]['averageScore'] += $score->getScoreEvaluation();
            }

            // Calculate average scores
            foreach ($statistics as &$stat) {
                if ($stat['totalScores'] > 0) {
                    $stat['averageScore'] /= $stat['totalScores'];
                }
            }

            return $this->render('score/user_scores_statistics.html.twig', [
                'user' => $user,
                'statistics' => $statistics,
            ]);
        } else return $this->render('error_modal.html.twig');
    }
}