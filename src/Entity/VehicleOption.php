<?php

namespace App\Entity;

use App\Repository\VehicleOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehicleOptionRepository::class)]
class VehicleOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Option>
     */
    #[ORM\ManyToMany(targetEntity: Option::class, inversedBy: 'vehicleOptions')]
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
