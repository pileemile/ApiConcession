<?php
namespace App\Tests\Entity;

use App\Entity\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    public function testCustomerEntity()
    {
        // Création d'une instance de l'entité Customer
        $customer = new Customer();

        // Test des setters
        $customer->setLastName('Doe');
        $customer->setFirstName('John');
        $customer->setEmail('john.doe@example.com');
        $customer->setPhone('1234567890');
        $customer->setAdress('123 Main St');
        $customer->setRegisterDate(new \DateTime());

        // Test des getters et vérification des valeurs
        $this->assertEquals('Doe', $customer->getLastName());
        $this->assertEquals('John', $customer->getFirstName());
        $this->assertEquals('john.doe@example.com', $customer->getEmail());
        $this->assertEquals('1234567890', $customer->getPhone());
        $this->assertEquals('123 Main St', $customer->getAdress());

        // Vérifie que la date d'inscription n'est pas nulle
        $this->assertInstanceOf(\DateTimeInterface::class, $customer->getRegisterDate());
    }
}
