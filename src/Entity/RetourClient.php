<?php

namespace App\Entity;

use App\Repository\RetourClientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RetourClientRepository::class)]
class RetourClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libeller = null;

    #[ORM\Column(nullable: true)]
    private ?int $notation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibeller(): ?string
    {
        return $this->libeller;
    }

    public function setLibeller(?string $libeller): static
    {
        $this->libeller = $libeller;

        return $this;
    }

    public function getNotation(): ?int
    {
        return $this->notation;
    }

    public function setNotation(?int $notation): static
    {
        $this->notation = $notation;

        return $this;
    }
}
