<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompteRepository::class)]
class Compte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'comptes')]
    private ?User $idUser = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $solde = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createddAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $idConseiller = null;

    #[ORM\OneToMany(mappedBy: 'idCompte', targetEntity: Transaction::class)]
    private Collection $transactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getSolde(): ?string
    {
        return $this->solde;
    }

    public function setSolde(?string $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getCreateddAt(): ?\DateTimeImmutable
    {
        return $this->createddAt;
    }

    public function setCreateddAt(?\DateTimeImmutable $createddAt): self
    {
        $this->createddAt = $createddAt;

        return $this;
    }

    public function getIdConseiller(): ?int
    {
        return $this->idConseiller;
    }

    public function setIdConseiller(?int $idConseiller): self
    {
        $this->idConseiller = $idConseiller;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setIdCompte($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getIdCompte() === $this) {
                $transaction->setIdCompte(null);
            }
        }

        return $this;
    }
}
