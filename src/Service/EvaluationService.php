<?php
namespace App\Service;

use App\Entity\Score;
use App\Entity\User;
use App\Repository\ScoreRepository;
use Doctrine\ORM\EntityManagerInterface;

 class EvaluationService
{
    private $entityManager;
    private $scoreRepository;

    public function __construct(ScoreRepository $scoreRepository,EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->scoreRepository=$scoreRepository;
    }

    public function evaluateEmployee(User $employee, Score $score): void
    {

        $score->setEmploye($employee);
        $this->entityManager->persist($score);
        $this->entityManager->flush();
    }
     /**
      * Get scores for a specific user by their ID.
      *
      * @param int $userId
      * @return Score[]
      */
     public function getScoresByUserId(int $userId): array
     {
         return $this->scoreRepository->createQueryBuilder('s')
             ->andWhere('s.employe = :userId')
             ->setParameter('userId', $userId)
             ->orderBy('s.created_At', 'DESC')
             ->getQuery()
             ->getResult();
     }
}
