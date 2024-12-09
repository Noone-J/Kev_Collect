<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
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

    #[ORM\Column(length: 255)]
    private ?string $image = null;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    // public function estDisponible(int $quantiteDemandee): bool
    // {
    //     $stock = $this->getStock();
        
    //     if (!$stock) {
    //         return false;
    //     }
        
    //     return $stock->getQuantiteStock() >= $quantiteDemandee;
    // }

    // private function getStock(): ?Stock
    // {
    //     // Implémentez ici la logique pour récupérer le stock correspondant au produit
    //     // Par exemple, vous pourriez utiliser Doctrine pour récupérer le stock par ID du produit
    //     // Retournez null si aucun stock n'est trouvé
    //     $total = 0;
    //     foreach($this->$produit as $produit){

    //     }
    // }
}
