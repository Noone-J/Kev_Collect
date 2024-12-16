<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $date;

    #[ORM\Column(nullable: false)]
    private int $prixCommande = 0;

    #[ORM\ManyToOne(targetEntity: Statut::class)]
    private ?Statut $leStatut = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $leUser = null;

    #[ORM\OneToMany(mappedBy: 'laCommande', targetEntity: DetailCommande::class, cascade: ['persist'])]
    private Collection $lesDetailsCommande;

    public function __construct()
    {
        $this->lesDetailsCommande = new ArrayCollection();
        $this->date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getPrixCommande(): ?int
    {
        return $this->prixCommande;
    }

    public function getLeStatut(): ?Statut
    {
        return $this->leStatut;
    }

    public function setLeStatut(?Statut $leStatut): self
    {
        $this->leStatut = $leStatut;
        return $this;
    }

    public function getLeUser(): ?User
    {
        return $this->leUser;
    }

    public function setLeUser(?User $leUser): self
    {
        $this->leUser = $leUser;
        return $this;
    }

    /**
     * @return Collection<int, DetailCommande>
     */
    public function getLesDetailsCommande(): Collection
    {
        return $this->lesDetailsCommande;
    }

    public function addLesDetailsCommande(DetailCommande $detailCommande): self
    {
        if (!$this->lesDetailsCommande->contains($detailCommande)) {
            $this->lesDetailsCommande->add($detailCommande);
            $detailCommande->setLaCommande($this);
        }
        $this->calculerPrixTotal();
        return $this;
    }

    public function removeLesDetailsCommande(DetailCommande $detailCommande): self
    {
        if ($this->lesDetailsCommande->removeElement($detailCommande)) {
            // set the owning side to null (unless already changed)
            if ($detailCommande->getLaCommande() === $this) {
                $detailCommande->setLaCommande(null);
            }
        }
        $this->calculerPrixTotal();
        return $this;
    }

    public function calculerPrixTotal()
    {
        $prixTotal = 0;
        foreach ($this->lesDetailsCommande as $detail) {
            $prixTotal += $detail->getPrixQuantite();
        }
        $this->setPrixCommande($prixTotal);
    }

    public function setPrixCommande(int $prixCommande): self
    {
        $this->prixCommande = $prixCommande;
        return $this;
    }
}
