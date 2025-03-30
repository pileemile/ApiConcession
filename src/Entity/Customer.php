<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\CustomerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;

#[ORM\Entity(repositoryClass: CustomerRepository::class)]
#[OA\Schema(
    schema: "Customer",
    description: "Représentation d'un client"
)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[OA\Property(type: 'integer', description: 'ID du client')]
    #[Groups(["getAllCustomers"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(type: 'string', maxLength: 100, description: 'Nom du client')]
    #[Groups(["getAllCustomers"])]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(type: 'string', maxLength: 100, description: 'Prénom du client')]
    #[Groups(["getAllCustomers"])]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(type: 'string', format: 'email', description: 'Email du client')]
    #[Groups(["getAllCustomers"])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(type: 'string', description: 'Téléphone du client')]
    #[Groups(["getAllCustomers"])]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    #[OA\Property(type: 'string', description: 'Adresse du client')]
    #[Groups(["getAllCustomers"])]
    private ?string $adress = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[OA\Property(type: 'string', format: 'date-time', description: 'Date d\'inscription du client')] // ✅ Ajout du format date
    #[Groups(["getAllCustomers"])]
    private ?\DateTimeInterface $registerDate = null;

    // Getters & Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;
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

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;
        return $this;
    }

    public function getRegisterDate(): ?\DateTimeInterface
    {
        return $this->registerDate;
    }

    public function setRegisterDate(\DateTimeInterface $registerDate): static
    {
        $this->registerDate = $registerDate;
        return $this;
    }
}
