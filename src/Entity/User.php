<?php

namespace App\Entity;

use App\Repository\UserRepository;
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
    #[ORM\Column(type:"datetime")]
    private $enabled;
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    public function __construct()
    {
        $this->roles = ['EMPLOYE'];
        $this->createdAt = new \DateTime();
        $this->enabled = false;
       
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
}
