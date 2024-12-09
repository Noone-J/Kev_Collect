<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite_stock = null;

    #[ORM\ManyToOne]
    private ?produit $leProduit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantiteStock(): ?int
    {
        return $this->quantite_stock;
    }

    public function setQuantiteStock(int $quantite_stock): static
    {
        $this->quantite_stock = $quantite_stock;

        return $this;
    }

    public function getLeProduit(): ?produit
    {
        return $this->leProduit;
    }

    public function setLeProduit(?produit $leProduit): static
    {
        $this->leProduit = $leProduit;

        return $this;
    }
}
