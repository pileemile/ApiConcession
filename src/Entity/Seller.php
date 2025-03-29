<?php

namespace App\Entity;

use App\Repository\SellerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SellerRepository::class)]
#[OA\Schema(
    schema: "Seller",
    description: "Représentation d'un Vendeur"
)]
class Seller
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[OA\Property(description: "ID du vendeur", type: "integer")]
    #[Groups(["getAllSellers"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[OA\Property(description: "Nom du vendeur", type: "string")]
    #[Groups(["getAllSellers"])]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    #[OA\Property(description: "Prénom du vendeur", type: "string")]
    #[Groups(["getAllSellers"])]
    private ?string $prenom = null;

    #[ORM\Column(length: 100)]
    #[OA\Property(description: "Email du vendeur", type: "string", format: "email")]
    #[Groups(["getAllSellers"])]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    #[OA\Property(description: "Téléphone du vendeur", type: "string")]
    #[Groups(["getAllSellers"])]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[OA\Property(description: "Date d'embauche du vendeur", type: "string", format: "date")]
    #[Groups(["getAllSellers"])]
    private ?\DateTimeInterface $dateEmbauche = null;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getDateEmbauche(): ?\DateTimeInterface
    {
        return $this->dateEmbauche;
    }

    public function setDateEmbauche(\DateTimeInterface $dateEmbauche): static
    {
        $this->dateEmbauche = $dateEmbauche;
        return $this;
    }
}
