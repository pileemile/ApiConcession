<?php

namespace App\Entity;

use App\Repository\SupplierRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SupplierRepository::class)]
class Supplier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["gatAllSupplier"])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(["gatAllSupplier"])]
    private ?string $name = null;

    #[ORM\Column(length: 150)]
    #[Groups(["gatAllSupplier"])]
    private ?string $contact = null;

    #[ORM\Column(length: 255)]
    #[Groups(["gatAllSupplier"])]
    private ?string $adress = null;

    #[ORM\Column(length: 150)]
    #[Groups(["gatAllSupplier"])]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    #[Groups(["gatAllSupplier"])]
    private ?string $phone = null;

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

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): static
    {
        $this->contact = $contact;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
}
