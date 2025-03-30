<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CustomerControllerTest extends WebTestCase
{
    public function testGetAllCustomers()
    {
        $client = static::createClient();

        // Effectue une requête GET sur l'endpoint API pour obtenir les clients
        $client->request('GET', '/api/customer');

        // Vérifie que la réponse HTTP est 200
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Vérifie que le JSON retourné contient les éléments du client
        $content = $client->getResponse()->getContent();
        $this->assertStringContainsString('"last_name":"Doe"', $content);  // Remplacer par la valeur réelle
        $this->assertStringContainsString('"first_name":"John"', $content); // Remplacer par la valeur réelle
    }



    public function testCreateCustomer()
    {
        $client = static::createClient();

        // Création d'un client via un POST avec des données JSON
        $client->request('POST', '/api/customers', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'last_name' => 'Doe',
            'first_name' => 'John',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'adress' => '123 Main St',
            'registerDate' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]));

        // Vérifie que la réponse HTTP est 201 (création réussie)
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        // Vérifie que le JSON retourné contient les données
        $content = $client->getResponse()->getContent();
        $this->assertStringContainsString('"lastName":"Doe"', $content); // Remplacer par la clé correcte en camelCase
        $this->assertStringContainsString('"firstName":"John"', $content); // Remplacer par la clé correcte en camelCase
    }


}
