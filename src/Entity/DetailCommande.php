<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DetailCommandeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DetailCommandeRepository::class)]
class DetailCommande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private ?int $quantite = null;

    #[ORM\Column(type: 'integer')]
    private ?int $prix_quantite = null;

    #[ORM\ManyToOne(targetEntity: Commande::class, inversedBy: 'lesDetailsCommande')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $laCommande = null;

    #[ORM\ManyToOne(targetEntity: Produit::class, inversedBy: 'lesDetailsCommande')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $leProduit = null;

    public function __construct(?Produit $produit = null, int $quantite = 0)
    {
        $this->leProduit = $produit;
        $this->quantite = $quantite;

        if ($produit && $quantite > 0) {
            $this->prix_quantite = $produit->getPrixUnitaire() * $quantite;
        } else {
            $this->prix_quantite = 0;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        if ($quantite <= 0) {
            throw new \InvalidArgumentException('La quantité doit être positive.');
        }

        $this->quantite = $quantite;

        // Mettre à jour automatiquement le prix_quantite
        if ($this->leProduit) {
            $this->prix_quantite = $this->leProduit->getPrixUnitaire() * $quantite;
        }

        return $this;
    }

    public function getPrixQuantite(): ?int
    {
        return $this->prix_quantite;
    }

    public function setPrixQuantite(int $prix_quantite): self
    {
        if ($prix_quantite < 0) {
            throw new \InvalidArgumentException('Le prix total doit être positif.');
        }

        $this->prix_quantite = $prix_quantite;
        return $this;
    }

    public function getLaCommande(): ?Commande
    {
        return $this->laCommande;
    }

    public function setLaCommande(?Commande $laCommande): self
    {
        $this->laCommande = $laCommande;
        return $this;
    }

    public function getLeProduit(): ?Produit
    {
        return $this->leProduit;
    }

    public function setLeProduit(?Produit $leProduit): self
    {
        $this->leProduit = $leProduit;

        // Recalculer le prix_quantite si la quantité est déjà définie
        if ($leProduit && $this->quantite) {
            $this->prix_quantite = $leProduit->getPrixUnitaire() * $this->quantite;
        }

        return $this;
    }
}
