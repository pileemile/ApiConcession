<?php

namespace App\Entity;

use App\Repository\ReparationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReparationRepository::class)]
class Reparation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getAllReparation"])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[Groups(["getAllReparation"])]
    private ?Vehicle $vehicle = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["getAllReparation"])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["getAllReparation"])]
    private ?\DateTimeInterface $repairDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(["getAllReparation"])]
    private ?string $cost = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    public function setVehicle(?Vehicle $vehicle): static
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRepairDate(): ?\DateTimeInterface
    {
        return $this->repairDate;
    }

    public function setRepairDate(\DateTimeInterface $repairDate): static
    {
        $this->repairDate = $repairDate;

        return $this;
    }

    public function getCost(): ?string
    {
        return $this->cost;
    }

    public function setCost(string $cost): static
    {
        $this->cost = $cost;

        return $this;
    }
}
