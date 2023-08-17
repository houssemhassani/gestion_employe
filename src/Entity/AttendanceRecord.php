<?php

namespace App\Entity;

use App\Repository\AttendanceRecordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AttendanceRecordRepository::class)]
class AttendanceRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'attendanceRecord', targetEntity: Attendance::class)]
    private Collection $attendancess;

    #[ORM\Column]
    private ?int $month = null;

    #[ORM\Column]
    private ?int $year = null;
    #[ORM\Column(nullable:true)]
    private ?float $extraHours;

    #[ORM\ManyToOne(inversedBy: 'attendancerecord')]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?float $totalOfAdvanceSalary = null;



    public function __construct()
    {
        $this->attendancess = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Attendance>
     */
    public function getAttendancess(): Collection
    {
        return $this->attendancess;
    }

    public function addAttendance(attendance $attendance): static
    {
        if (!$this->attendancess->contains($attendance)) {
            $this->attendancess->add($attendance);
            $attendance->setAttendanceRecord($this);
        }

        return $this;
    }

    public function removeAttendance(attendance $attendance): static
    {
        if ($this->attendancess->removeElement($attendance)) {
            // set the owning side to null (unless already changed)
            if ($attendance->getAttendanceRecord() === $this) {
                $attendance->setAttendanceRecord(null);
            }
        }

        return $this;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): static
    {
        $this->month = $month;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTotalOfAdvanceSalary(): ?float
    {
        return $this->totalOfAdvanceSalary;
    }

    public function setTotalOfAdvanceSalary(?float $totalOfAdvanceSalary): static
    {
        $this->totalOfAdvanceSalary = $totalOfAdvanceSalary;

        return $this;
    }


}
