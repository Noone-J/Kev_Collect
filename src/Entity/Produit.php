<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libeller = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $prix_unitaire = null;

    /**
     * @var Collection<int, Stock>
     */
    #[ORM\OneToMany(targetEntity: Stock::class, mappedBy: 'leProduit')]
    private Collection $lesStock;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    public function __construct()
    {
        $this->lesStock = new ArrayCollection();
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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixUnitaire(): ?int
    {
        return $this->prix_unitaire;
    }

    public function setPrixUnitaire(int $prix_unitaire): static
    {
        $this->prix_unitaire = $prix_unitaire;

        return $this;
    }

    /**
     * @return Collection<int, Stock>
     */
    public function getLesStock(): Collection
    {
        return $this->lesStock;
    }

    public function addLesStock(Stock $lesStock): static
    {
        if (!$this->lesStock->contains($lesStock)) {
            $this->lesStock->add($lesStock);
            $lesStock->setLeProduit($this);
        }

        return $this;
    }

    public function removeLesStock(Stock $lesStock): static
    {
        if ($this->lesStock->removeElement($lesStock)) {
            // set the owning side to null (unless already changed)
            if ($lesStock->getLeProduit() === $this) {
                $lesStock->setLeProduit(null);
            }
        }

        return $this;
    }

    public function estDisponible(int $quantiteDemandee): bool
    {
        $quantiteTotale = $this->getQuantiteTotaleStock();
        return $quantiteTotale >= $quantiteDemandee;
    }

    private function getQuantiteTotaleStock(): int
    {
        $total = 0;
        foreach ($this->getLesStock() as $stock) {
            $total += $stock->getQuantiteStock();
        }
        return $total;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

}
