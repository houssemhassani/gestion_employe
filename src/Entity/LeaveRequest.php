<?php

namespace App\Entity;

use App\Repository\LeaveRequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeaveRequestRepository::class)]
class LeaveRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'leaveRequests')]
    private ?User $employe = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $SortedDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $returnedDate = null;

    #[ORM\Column]
    private ?int $numberOfDayLeaved = null;
    #[ORM\Column]
    private ?bool $typeOfLeaveRequest=null;


    public function getTypeOfLeaveRequest(): ?bool{
        return $this->typeOfLeaveRequest;
    }
    public function setTypeOfLeaveRequest(bool $typeOf){
        $this->typeOfLeaveRequest=$typeOf;
    }
    public function __construct()
    {
        $this->employee = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmploye(): ?user
    {
        return $this->employe;
    }

    public function setEmploye(?user $employe): static
    {
        $this->employe = $employe;

        return $this;
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

    public function getSortedDate(): ?\DateTimeInterface
    {
        return $this->SortedDate;
    }

    public function setSortedDate(\DateTimeInterface $SortedDate): static
    {
        $this->SortedDate = $SortedDate;

        return $this;
    }

    public function getReturnedDate(): ?\DateTimeInterface
    {
        return $this->returnedDate;
    }

    public function setReturnedDate(\DateTimeInterface $returnedDate): static
    {
        $this->returnedDate = $returnedDate;

        return $this;
    }

    public function getNumberOfDayLeaved(): ?int
    {
        return $this->numberOfDayLeaved;
    }

    public function setNumberOfDayLeaved(int $numberOfDayLeaved): static
    {
        $this->numberOfDayLeaved = $numberOfDayLeaved;

        return $this;
    }

    
}
