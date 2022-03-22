<?php

namespace App\Entity;

use App\Repository\AchatsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AchatsRepository::class)]
class Achats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Goodies::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $goodies;

    #[ORM\ManyToOne(targetEntity: Commande::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $commande;

    #[ORM\Column(type: 'integer')]
    private $nombres;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGoodies(): ?Goodies
    {
        return $this->goodies;
    }

    public function setGoodies(?Goodies $goodies): self
    {
        $this->goodies = $goodies;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        $this->commande = $commande;

        return $this;
    }

    public function getNombres(): ?int
    {
        return $this->nombres;
    }

    public function setNombres(int $nombres): self
    {
        $this->nombres = $nombres;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
