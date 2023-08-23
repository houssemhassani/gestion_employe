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

    public function evaluateEmployee(User $employee, Score $score): void
    {

        $score->setEmploye($employee);
        $this->entityManager->persist($score);
        $this->entityManager->flush();
    }
}
