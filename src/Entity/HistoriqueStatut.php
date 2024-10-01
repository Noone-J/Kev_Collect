<?php

namespace App\Entity;

use App\Repository\HistoriqueStatutRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriqueStatutRepository::class)]
class HistoriqueStatut
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_changement = null;

    #[ORM\Column(length: 255)]
    private ?string $modifie_par = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateChangement(): ?\DateTimeInterface
    {
        return $this->date_changement;
    }

    public function setDateChangement(\DateTimeInterface $date_changement): static
    {
        $this->date_changement = $date_changement;

        return $this;
    }

    public function getModifiePar(): ?string
    {
        return $this->modifie_par;
    }

    public function setModifiePar(string $modifie_par): static
    {
        $this->modifie_par = $modifie_par;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
