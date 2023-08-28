<?php

namespace App\Entity;

use App\Repository\ScoreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScoreRepository::class)]
class Score
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $scoreEvaluation = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentEvaluation = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $created_At = null;



    #[ORM\ManyToOne(inversedBy: 'scores')]
    private ?User $employe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScoreEvaluation(): ?float
    {
        return $this->scoreEvaluation;
    }

    public function setScoreEvaluation(?float $scoreEvaluation): static
    {
        $this->scoreEvaluation = $scoreEvaluation;

        return $this;
    }

    public function getCommentEvaluation(): ?string
    {
        return $this->commentEvaluation;
    }

    public function setCommentEvaluation(?string $commentEvaluation): static
    {
        $this->commentEvaluation = $commentEvaluation;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_At;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_At): static
    {
        $this->created_At = $created_At;

        return $this;
    }





    public function getEmploye(): ?User
    {
        return $this->employe;
    }

    public function setEmploye(?User $employe): static
    {
        $this->employe = $employe;

        return $this;
    }
}
