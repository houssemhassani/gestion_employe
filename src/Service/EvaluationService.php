<?php
namespace App\Service;

use App\Entity\Score;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

 class EvaluationService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function evaluateEmployee(User $employee, float $score, string $comment): void
    {
        $evaluation = new Score();
        $evaluation->setScoreEvaluation($score);
        $evaluation->setCommentEvaluation($comment);
        $evaluation->setEmployee($employee);
        $this->entityManager->persist($evaluation);
        $this->entityManager->flush();
    }
}
