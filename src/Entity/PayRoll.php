<?php

namespace App\Entity;

use App\Repository\PayRollRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PayRollRepository::class)]
class PayRoll
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?float $numberOfDaysPresent = null;

    #[ORM\Column]
    private ?float $primeEvaluationTotal = null;

    #[ORM\Column]
    private ?float $TotalSalary = null;

    #[ORM\ManyToOne(inversedBy: 'payrolls')]
    private ?User $employee = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    

    
    
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getNumberOfDaysPresent(): ?float
    {
        return $this->numberOfDaysPresent;
    }

    public function setNumberOfDaysPresent(float $numberOfDaysPresent): static
    {
        $this->numberOfDaysPresent = $numberOfDaysPresent;

        return $this;
    }

    public function getPrimeEvaluationTotal(): ?float
    {
        return $this->primeEvaluationTotal;
    }

    public function setPrimeEvaluationTotal(float $primeEvaluationTotal): static
    {
        $this->primeEvaluationTotal = $primeEvaluationTotal;

        return $this;
    }

    public function getTotalSalary(): ?float
    {
        return $this->TotalSalary;
    }

    public function setTotalSalary(float $TotalSalary): static
    {
        $this->TotalSalary = $TotalSalary;

        return $this;
    }

    public function getEmployee(): ?User
    {
        return $this->employee;
    }

    public function setEmployee(?User $employee): static
    {
        $this->employee = $employee;

        return $this;
    }
}
