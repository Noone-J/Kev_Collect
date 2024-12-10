<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $prix_commande = null;

    #[ORM\ManyToOne(inversedBy: 'LesCommande')]
    private ?User $leUser = null;

    #[ORM\ManyToOne(inversedBy: 'lesCommande')]
    private ?Statut $leStatut = null;

    /**
     * @var Collection<int, DetailCommande>
     */
    #[ORM\OneToMany(targetEntity: DetailCommande::class, mappedBy: 'laCommande')]
    private Collection $lesDetailsCommande;

    public function __construct()
    {
        $this->lesDetailsCommande = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPrixCommande(): ?int
    {
        return $this->prix_commande;
    }

    public function setPrixCommande(int $prix_commande): static
    {
        $this->prix_commande = $prix_commande;

        return $this;
    }

    public function getLeUser(): ?User
    {
        return $this->leUser;
    }

    public function setLeUser(?User $leUser): static
    {
        $this->leUser = $leUser;

        return $this;
    }

    public function getLeStatut(): ?Statut
    {
        return $this->leStatut;
    }

    public function setLeStatut(?Statut $leStatut): static
    {
        $this->leStatut = $leStatut;

        return $this;
    }

    /**
     * @return Collection<int, DetailCommande>
     */
    public function getLesDetailsCommande(): Collection
    {
        return $this->lesDetailsCommande;
    }

    public function addLesDetailsCommande(DetailCommande $lesDetailsCommande): static
    {
        if (!$this->lesDetailsCommande->contains($lesDetailsCommande)) {
            $this->lesDetailsCommande->add($lesDetailsCommande);
            $lesDetailsCommande->setLaCommande($this);
        }

        return $this;
    }

    public function removeLesDetailsCommande(DetailCommande $lesDetailsCommande): static
    {
        if ($this->lesDetailsCommande->removeElement($lesDetailsCommande)) {
            // set the owning side to null (unless already changed)
            if ($lesDetailsCommande->getLaCommande() === $this) {
                $lesDetailsCommande->setLaCommande(null);
            }
        }

        return $this;
    }
}
