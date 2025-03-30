<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SellerControllerTest extends WebTestCase
{
    public function testGetAllSellers()
    {
        $client = static::createClient();

        // Effectue une requête GET sur l'endpoint API pour obtenir les vendeurs
        $client->request('GET', '/api/sellers');

        // Vérifie que la réponse HTTP est 200
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Vérifie que le JSON retourné contient les éléments du vendeur
        $content = $client->getResponse()->getContent();
        $this->assertStringContainsString('"name":"Smith"', $content);  // Remplace par les valeurs réelles
        $this->assertStringContainsString('"prenom":"Alex"', $content); // Remplace par les valeurs réelles
    }


    public function testCreateSeller()
    {
        $client = static::createClient();

        // Création d'un vendeur via un POST avec des données JSON
        $client->request('POST', '/api/sellers', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Smith',
            'prenom' => 'Alex',
            'email' => 'alex.smith@example.com',
            'telephone' => '0987654321',
            'dateEmbauche' => (new \DateTime())->format('Y-m-d'),
        ]));

        // Vérifie que la réponse HTTP est 201 (création réussie)
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        // Vérifie que le JSON retourné contient les données
        $content = $client->getResponse()->getContent();
        $this->assertStringContainsString('"name":"Smith"', $content); // Remplace par la clé correcte
        $this->assertStringContainsString('"prenom":"Alex"', $content); // Remplace par la clé correcte
    }

}
