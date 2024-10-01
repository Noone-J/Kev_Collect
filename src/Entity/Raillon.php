<?php

namespace App\Entity;

use App\Repository\RaillonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RaillonRepository::class)]
class Raillon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type_raillon = null;

    #[ORM\Column(length: 255)]
    private ?string $emplacement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeRaillon(): ?string
    {
        return $this->type_raillon;
    }

    public function setTypeRaillon(string $type_raillon): static
    {
        $this->type_raillon = $type_raillon;

        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(string $emplacement): static
    {
        $this->emplacement = $emplacement;

        return $this;
    }
}
