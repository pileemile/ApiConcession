<?php

namespace App\Entity;

use App\Repository\VehicleOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VehicleOptionRepository::class)]
class VehicleOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getAllVehicleOption"])]
    private ?int $id = null;

    /**
     * @var Collection<int, Option>
     */
    #[ORM\ManyToMany(targetEntity: Option::class, inversedBy: 'vehicleOption')]
    #[ORM\JoinTable(name: "vehicle_option_option")]
    #[Groups(["getAllVehicleOption"])]
    private Collection $option;

    public function __construct()
    {
        $this->option = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Option>
     */
    public function getOption(): Collection
    {
        return $this->option;
    }

    public function addOption(Option $option): static
    {
        if (!$this->option->contains($option)) {
            $this->option->add($option);
        }

        return $this;
    }

    public function removeOption(Option $option): static
    {
        $this->option->removeElement($option);

        return $this;
    }
}
