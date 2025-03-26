<?php
namespace App\Controller;

use App\Entity\Sale;
use App\Entity\Vehicle;
use App\Repository\SaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends AbstractController
{
    #[Route('/api/sale', name: 'reparation_list', methods: ['GET'])]
    #[OA\Get(
        path: "/api/sale",
        summary: "Liste des sale",
        tags: ["Sale"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des sale",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Sale")

                )
            )
        ]
    )]
    public function getAllSale(
        SaleRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $sale = $repository->findAll();
        $jsonCustomers = $serializer->serialize($sale, 'json', ["groups" => "getAllSale"]);

        return new JsonResponse($jsonCustomers, Response::HTTP_OK, [], true);
    }

    #[Route('/api/sale', name: 'create_sale', methods: ['POST'])]
    #[OA\Post(
        summary: "Ajouter une nouvelle sale",
        description: "Ajoute une sale en base de données",
        tags: ["Sale"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                required: ["saleDate","salePrice","Vehicle",],
                properties: [
                    new OA\Property(property: "saleDate", type: "string", example: "2021-10-10"),
                    new OA\Property(property: "salePrice", type: "float", example: "1000.000"),
                    new OA\Property(property: "Vehicle", type: "string", example: "1")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Sale ajouté avec succès",
                content: new OA\JsonContent(ref: "#/components/schemas/Sale")
            ),
            new OA\Response(
                response: 400, description: "Erreur de validation"
            )
        ]
    )]
    public function addSale(
        Request $request,
        SerializerInterface $serializer,
        SaleRepository $reparationRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $sale = $serializer->deserialize(
            $request->getContent(),
            Reparation::class,
            'json'
        );

        $entityManager->persist($sale);
        $entityManager->flush();
        return new JsonResponse(
            $serializer->serialize($sale, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );
    }

    #[Route('/api/sale/{id}', name: 'get_sale', methods: ['GET'])]
    #[OA\Get(
        summary: "Récupérer une sale par ID",
        description: "Retourne une sale en fonction de son ID",
        tags: ["Sale"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID de la sale"
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "sale trouvé",
                content: new OA\JsonContent(ref: "#/components/schemas/Sale")
            ),
            new OA\Response(
                response: 404,
                description: "sale non trouvé"
            )
        ]
    )]
    public function getSaleById(
        int $id,
        SaleRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse {
        $sale = $repository->find($id);

        if (!$sale) {
            return new JsonResponse(['message' => 'Sale non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $jsonCustomer = $serializer->serialize($sale, 'json', ["groups" => "getAllSale"]);
        return new JsonResponse($jsonCustomer, Response::HTTP_OK, [], true);
    }

    #[Route('/api/sale/{id}', name: 'delete_sale', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Supprimer une sale",
        description: "Supprime une sale en fonction de son ID",
        tags: ["Sale"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID due la sale à supprimer"
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "sale supprimé avec succès"
            ),
            new OA\Response(
                response: 404,
                description: "sale non trouvé"
            )
        ]
    )]
    public function deleteSale(
        int $id,
        SaleRepository $repository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $sale = $repository->find($id);

        if (!$sale) {
            return new JsonResponse(['message' => 'sale non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($sale);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
