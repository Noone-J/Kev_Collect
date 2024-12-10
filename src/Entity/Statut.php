<?php

namespace App\Entity;

use App\Repository\StatutRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatutRepository::class)]
class Statut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libeller = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'leStatut')]
    private Collection $lesCommande;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?HistoriqueStatut $leHistoriqueStatut = null;

    public function __construct()
    {
        $this->lesCommande = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibeller(): ?string
    {
        return $this->libeller;
    }

    public function setLibeller(string $libeller): static
    {
        $this->libeller = $libeller;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getLesCommande(): Collection
    {
        return $this->lesCommande;
    }

    public function addLesCommande(Commande $lesCommande): static
    {
        if (!$this->lesCommande->contains($lesCommande)) {
            $this->lesCommande->add($lesCommande);
            $lesCommande->setLeStatut($this);
        }

        return $this;
    }

    public function removeLesCommande(Commande $lesCommande): static
    {
        if ($this->lesCommande->removeElement($lesCommande)) {
            // set the owning side to null (unless already changed)
            if ($lesCommande->getLeStatut() === $this) {
                $lesCommande->setLeStatut(null);
            }
        }

        return $this;
    }

    public function getLeHistoriqueStatut(): ?HistoriqueStatut
    {
        return $this->leHistoriqueStatut;
    }

    public function setLeHistoriqueStatut(?HistoriqueStatut $leHistoriqueStatut): static
    {
        $this->leHistoriqueStatut = $leHistoriqueStatut;

        return $this;
    }

}
