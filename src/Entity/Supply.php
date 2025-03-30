<?php

namespace App\Entity;

use App\Repository\SupplyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SupplyRepository::class)]
class Supply
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getAllSupply"])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[Groups(["getAllSupply"])]
    private ?supplier $supplier = null;

    #[ORM\ManyToOne]
    #[Groups(["getAllSupply"])]
    private ?Vehicle $vehicle = null;

    #[ORM\Column]
    #[Groups(["getAllSupply"])]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["getAllSupply"])]
    private ?\DateTimeInterface $supplyDate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(["getAllSupply"])]
    private ?string $purchasePrice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSupplier(): ?supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?supplier $supplier): static
    {
        $this->supplier = $supplier;

        return $this;
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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getSupplyDate(): ?\DateTimeInterface
    {
        return $this->supplyDate;
    }

    public function setSupplyDate(\DateTimeInterface $supplyDate): static
    {
        $this->supplyDate = $supplyDate;

        return $this;
    }

    public function getPurchasePrice(): ?string
    {
        return $this->purchasePrice;
    }

    public function setPurchasePrice(string $purchasePrice): static
    {
        $this->purchasePrice = $purchasePrice;

        return $this;
    }
}
