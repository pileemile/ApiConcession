<?php

namespace App\Entity;

use App\Repository\OptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OptionRepository::class)]
#[OA\Schema(
    schema: "Options",
    description: "Représentation d'une option"
)]
class Option
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getAllOption"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(["getAllOption"])]
    private ?string $name = null;

    /**
     * @var Collection<int, VehicleOption>
     */
    #[ORM\ManyToMany(targetEntity: VehicleOption::class, mappedBy: 'option')]
    #[Groups(["getAllOption"])]
    private Collection $vehicleOptions;

    public function __construct()
    {
        $this->vehicleOptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, VehicleOption>
     */
    public function getVehicleOptions(): Collection
    {
        return $this->vehicleOptions;
    }

    public function addVehicleOption(VehicleOption $vehicleOption): static
    {
        if (!$this->vehicleOptions->contains($vehicleOption)) {
            $this->vehicleOptions->add($vehicleOption);
            $vehicleOption->addOption($this);
        }

        return $this;
    }

    public function removeVehicleOption(VehicleOption $vehicleOption): static
    {
        if ($this->vehicleOptions->removeElement($vehicleOption)) {
            $vehicleOption->removeOption($this);
        }

        return $this;
    }
}
