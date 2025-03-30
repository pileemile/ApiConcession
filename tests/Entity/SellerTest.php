<?php
namespace App\Tests\Entity;

use App\Entity\Seller;
use PHPUnit\Framework\TestCase;

class SellerTest extends TestCase
{
    public function testSellerEntity()
    {
        // Création d'une instance de l'entité Seller
        $seller = new Seller();

        // Test des setters
        $seller->setName('Smith');
        $seller->setPrenom('Alex');
        $seller->setEmail('alex.smith@example.com');
        $seller->setTelephone('0987654321');
        $seller->setDateEmbauche(new \DateTime());

        // Test des getters et vérification des valeurs
        $this->assertEquals('Smith', $seller->getName());
        $this->assertEquals('Alex', $seller->getPrenom());
        $this->assertEquals('alex.smith@example.com', $seller->getEmail());
        $this->assertEquals('0987654321', $seller->getTelephone());

        // Vérifie que la date d'embauche n'est pas nulle
        $this->assertInstanceOf(\DateTimeInterface::class, $seller->getDateEmbauche());
    }
}
