<?php

namespace App\Entity;

use App\Repository\LeaveRequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LeaveRequestRepository::class)]
class LeaveRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'leaveRequests')]
    private ?user $employe = null;



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

    
}
