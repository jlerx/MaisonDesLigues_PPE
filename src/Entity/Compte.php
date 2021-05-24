<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CompteRepository::class)
 */
class Compte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numLicence;

    /**
     * @ORM\OneToOne(targetEntity=Licencie::class, mappedBy="compte", cascade={"persist", "remove"})
     */
    private $licencie;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="compte", cascade={"persist", "remove"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNumLicence(): ?string
    {
        return $this->numLicence;
    }

    public function setNumLicence(string $numLicence): self
    {
        $this->numLicence = $numLicence;

        return $this;
    }

    public function getLicencie(): ?Licencie
    {
        return $this->licencie;
    }

    public function setLicencie(?Licencie $licencie): self
    {
        // unset the owning side of the relation if necessary
        if ($licencie === null && $this->licencie !== null) {
            $this->licencie->setCompte(null);
        }

        // set the owning side of the relation if necessary
        if ($licencie !== null && $licencie->getCompte() !== $this) {
            $licencie->setCompte($this);
        }

        $this->licencie = $licencie;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        // set the owning side of the relation if necessary
        if ($user->getCompte() !== $this) {
            $user->setCompte($this);
        }

        $this->user = $user;

        return $this;
    }
}
