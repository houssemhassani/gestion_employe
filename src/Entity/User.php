<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id ;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;
    #[ORM\Column]
    private ?string $cin = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;
    #[ORM\Column]
    private ?string $nom = null;
    #[ORM\Column]
    private ?string $prenom = null;
    #[ORM\Column]
    private ?string $numTel = null;

    /**
     * @ORM\Column(type="datetime")
     */
    #[ORM\Column(type:"datetime")]
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    #[ORM\Column(type:"boolean")]
    private $enabled;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    #[ORM\ManyToMany(targetEntity: Formation::class, mappedBy: 'employes')]
    private Collection $formations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: AttendanceRecord::class)]
    private Collection $attendancerecord;

    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: LeaveRequest::class)]
    private Collection $leaveRequests;

    

    #[ORM\Column]
    private ?float $salary = null;

    #[ORM\OneToMany(mappedBy: 'employee', targetEntity: PayRoll::class)]
    private Collection $payrolls;

    #[ORM\OneToMany(mappedBy: 'employe', targetEntity: Score::class)]
    private Collection $scores;

    public function __construct()
    {
        $this->roles = ['EMPLOYE'];
        $this->createdAt = new \DateTime();
        $this->enabled = false;
        $this->formations = new ArrayCollection();
        $this->attendancerecord = new ArrayCollection();
        $this->leaveRequests = new ArrayCollection();
        $this->payrolls = new ArrayCollection();
        $this->scores = new ArrayCollection();
       
    }


  

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
    public function getToken(): ?string
    {
        return $this->token;
    }
    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    public function getNumTel(): ?string
    {
        return $this->numTel;
    }
    public function getNom(): ?string 
    {
        return $this->nom;
    }
    public function getPrenom(): ?string 
    {
        return $this->prenom;
    }
    public function setNumTel(string $numTel):static
    {
        $this->numTel=$numTel;
        return $this;
    }
    public function setNom(string $numTel):static
    {
        $this->nom=$numTel;
        return $this;
    }
    public function setPrenom(string $numTel):static
    {
        $this->prenom=$numTel;
        return $this;
    }
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
    public function setCin(string $email): static
    {
        $this->cin = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has EMPLOYE
        //$roles[] = 'EMPLOYE';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Formation>
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->addEmploye($this);
        }

        return $this;
    }

    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation)) {
            $formation->removeEmploye($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, AttendanceRequest>
     */
    public function getAttendancerecord(): Collection
    {
        return $this->attendancerecord;
    }

    public function addAttendancerecord(AttendanceRecord $attendancerecord): static
    {
        if (!$this->attendancerecord->contains($attendancerecord)) {
            $this->attendancerecord->add($attendancerecord);
            $attendancerecord->setUser($this);
        }

        return $this;
    }

    public function removeAttendancerecord(AttendanceRecord $attendancerecord): static
    {
        if ($this->attendancerecord->removeElement($attendancerecord)) {
            // set the owning side to null (unless already changed)
            if ($attendancerecord->getUser() === $this) {
                $attendancerecord->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, LeaveRequest>
     */
    public function getLeaveRequests(): Collection
    {
        return $this->leaveRequests;
    }

    public function addLeaveRequest(LeaveRequest $leaveRequest): static
    {
        if (!$this->leaveRequests->contains($leaveRequest)) {
            $this->leaveRequests->add($leaveRequest);
            $leaveRequest->setEmploye($this);
        }

        return $this;
    }

    public function removeLeaveRequest(LeaveRequest $leaveRequest): static
    {
        if ($this->leaveRequests->removeElement($leaveRequest)) {
            // set the owning side to null (unless already changed)
            if ($leaveRequest->getEmploye() === $this) {
                $leaveRequest->setEmploye(null);
            }
        }

        return $this;
    }

    

   

   
    public function getSalary(): ?float
    {
        return $this->salary;
    }

    public function setSalary(float $salary): static
    {
        $this->salary = $salary;

        return $this;
    }

    /**
     * @return Collection<int, Payroll>
     */
    public function getPayrolls(): Collection
    {
        return $this->payrolls;
    }

    public function addPayroll(Payroll $payroll): static
    {
        if (!$this->payrolls->contains($payroll)) {
            $this->payrolls->add($payroll);
            $payroll->setEmployee($this);
        }

        return $this;
    }

    public function removePayroll(Payroll $payroll): static
    {
        if ($this->payrolls->removeElement($payroll)) {
            // set the owning side to null (unless already changed)
            if ($payroll->getEmployee() === $this) {
                $payroll->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Score>
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): static
    {
        if (!$this->scores->contains($score)) {
            $this->scores->add($score);
            $score->setEmploye($this);
        }

        return $this;
    }

    public function removeScore(Score $score): static
    {
        if ($this->scores->removeElement($score)) {
            // set the owning side to null (unless already changed)
            if ($score->getEmploye() === $this) {
                $score->setEmploye(null);
            }
        }

        return $this;
    }
}
