<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use App\Repository\CustomerRepository;
use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;


final class CustomerController extends AbstractController
{
    #[Route('/api/customer', name: 'customer_list', methods: ['GET'])]
    #[OA\Get(
        path: "/api/customer",
        summary: "Liste des clients",
        tags: ["Customer"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des clients",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Customer")

                )
            )
        ]
    )]

    public function getAllCustomers(
        CustomerRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse {
        $customers = $repository->findAll();
        $jsonCustomers = $serializer->serialize($customers, 'json', ["groups" => "getAllCustomers"]);

        return new JsonResponse($jsonCustomers, Response::HTTP_OK, [], true);
    }
    #[Route('/api/customers', name: 'add_customer', methods: ['POST'])]
    #[OA\Post(
        summary: "Ajouter un nouveau client",
        description: "Ajoute un client en base de données",
        tags: ["Customer"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                required: ["first_name", "last_name", "email", "phone", "adress", "registerDate"],
                properties: [
                    new OA\Property(property: "first_name", type: "string", example: "John"),
                    new OA\Property(property: "last_name", type: "string", example: "Doe"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "john.doe@example.com"),
                    new OA\Property(property: "phone", type: "string", example: "0601020304"),
                    new OA\Property(property: "adress", type: "string", example: "123 Rue de Paris"),
                    new OA\Property(property: "registerDate", type: "string", format: "date-time", example: "2025-03-23T10:00:00Z"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Client ajouté avec succès",
                content: new OA\JsonContent(ref: "#/components/schemas/Customer")
            ),
            new OA\Response(
                response: 400,
                description: "Erreur de validation"
            )
        ]
    )]

    public function addCustomer(
        Request $request,
        SerializerInterface $serializer,
        CustomerRepository $customerRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $customer = $serializer->deserialize(
            $request->getContent(),
            Customer::class,
            'json'
        );

        $entityManager->persist($customer);
        $entityManager->flush();
        return new JsonResponse(
            $serializer->serialize($customer, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );
    }

    #[Route('/api/customers/{id}', name: 'get_customer', methods: ['GET'])]
    #[OA\Get(
        summary: "Récupérer un client par ID",
        description: "Retourne un client en fonction de son ID",
        tags: ["Customer"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID du client"
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Client trouvé",
                content: new OA\JsonContent(ref: "#/components/schemas/Customer")
            ),
            new OA\Response(
                response: 404,
                description: "Client non trouvé"
            )
        ]
    )]
    public function getCustomerById(
        int $id,
        CustomerRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse {
        $customer = $repository->find($id);

        if (!$customer) {
            return new JsonResponse(['message' => 'Client non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $jsonCustomer = $serializer->serialize($customer, 'json', ["groups" => "getAllCustomers"]);
        return new JsonResponse($jsonCustomer, Response::HTTP_OK, [], true);
    }

    #[Route('/api/customers/{id}', name: 'delete_customer', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Supprimer un client",
        description: "Supprime un client en fonction de son ID",
        tags: ["Customer"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID du client à supprimer"
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "Client supprimé avec succès"
            ),
            new OA\Response(
                response: 404,
                description: "Client non trouvé"
            )
        ]
    )]
    public function deleteCustomer(
        int $id,
        CustomerRepository $repository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $customer = $repository->find($id);

        if (!$customer) {
            return new JsonResponse(['message' => 'Client non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($customer);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }


}
