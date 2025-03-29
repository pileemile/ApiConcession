<?php

namespace App\Entity;

use App\Enum\FuelType;
use App\Enum\TransmissionType;
use App\Enum\VehicleStatus;
use App\Enum\VehicleType;
use App\Repository\VehicleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getAllVehicle"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getAllVehicle"])]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getAllVehicle"])]
    private ?string $model = null;

    #[ORM\Column]
    #[Groups(["getAllVehicle"])]
    private ?int $year = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(["getAllVehicle"])]
    private ?float $price = null;

    #[ORM\Column]
    #[Groups(["getAllVehicle"])]
    private ?int $mileage = null;

    #[ORM\Column(type: Types::STRING, length: 20, enumType: VehicleType::class)]
    #[Groups(["getAllVehicle"])]
    private VehicleType $type;

    #[ORM\Column(length: 20)]
    #[Groups(["getAllVehicle"])]
    private FuelType $fuelType;

    #[ORM\Column(length: 20)]
    #[Groups(["getAllVehicle"])]
    private TransmissionType $transmissionType;

    #[ORM\Column(length: 20)]
    #[Groups(["getAllVehicle"])]
    private VehicleStatus $status;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["getAllVehicle"])]
    private ?\DateTimeInterface $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getMileage(): ?int
    {
        return $this->mileage;
    }

    public function setMileage(int $mileage): static
    {
        $this->mileage = $mileage;

        return $this;
    }

    public function getType(): ?VehicleType
    {
        return $this->type;
    }

    public function setType(VehicleType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getFuelType(): ?FuelType
    {
        return $this->fuelType;
    }

    public function setFuelType(FuelType $fuelType): static
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    public function getTransmissionType(): ?TransmissionType
    {
        return $this->transmissionType;
    }

    public function setTransmissionType(TransmissionType $transmissionType): static
    {
        $this->transmissionType = $transmissionType;

        return $this;
    }

    public function getStatus(): ?VehicleStatus
    {
        return $this->status;
    }

    public function setStatus(VehicleStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
