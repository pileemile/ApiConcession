<?php
namespace App\Tests\Entity;

use App\Entity\Supplier;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Email;

class SupplierTest extends TestCase
{
    public function testSupplierEntity()
    {
        // Création d'une instance de l'entité Supplier
        $supplier = new Supplier();

        // Test des setters
        $supplier->setName('ABC Supplier');
        $supplier->setContact('John Smith');
        $supplier->setAdress('456 Supplier St');
        $supplier->setEmail('supplier@example.com');
        $supplier->setPhone('9876543210');

        // Test des getters et vérification des valeurs
        $this->assertEquals('ABC Supplier', $supplier->getName());
        $this->assertEquals('John Smith', $supplier->getContact());
        $this->assertEquals('456 Supplier St', $supplier->getAdress());
        $this->assertEquals('supplier@example.com', $supplier->getEmail());
        $this->assertEquals('9876543210', $supplier->getPhone());
    }

}
