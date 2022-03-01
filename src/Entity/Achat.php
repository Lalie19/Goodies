<?php

namespace App\Entity;

use App\Repository\AchatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AchatRepository::class)]
class Achat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Goodies::class)]
    private $goodies;

    #[ORM\Column(type: 'integer')]
    private $nombre;

    #[ORM\ManyToOne(targetEntity: Commandes::class)]
    private $commandes;

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

    public function getNombre(): ?int
    {
        return $this->nombre;
    }

    public function setNombre(int $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getCommandes(): ?Commandes
    {
        return $this->commandes;
    }

    public function setCommandes(?Commandes $commandes): self
    {
        $this->commandes = $commandes;

        return $this;
    }
}
