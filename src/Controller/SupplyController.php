<?php
namespace App\Controller;

use App\Entity\Supply;
use App\Entity\Vehicle;
use App\Entity\Supplier;
use App\Repository\SupplyRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class SupplyController extends AbstractController
{
    #[Route('/api/supply', name: 'supply_list', methods: ['GET'])]
    #[OA\Get(
        path: "/api/supply",
        summary: "Liste des supply",
        tags: ["Supply"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des supply",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Supply")

                )
            )
        ]
    )]
    public function getAllSupply(
        SupplyRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $supply = $repository->findAll();
        $jsonCustomers = $serializer->serialize($supply, 'json', ["groups" => "getAllSupply"]);

        return new JsonResponse($jsonCustomers, Response::HTTP_OK, [], true);
    }

    #[Route('/api/supply', name: 'create_supply', methods: ['POST'])]
    #[OA\Post(
        summary: "Ajouter une nouvelle supply",
        description: "Ajoute une supply en base de données",
        tags: ["Supply"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                required: ["supplier","vehicle","quantity", "supplyDate", "purchasePrice"],
                properties: [
                    new OA\Property(property: "supplier", ref: "#/components/schemas/Supplier"),
                    new OA\Property(property: "vehicle",ref: "#/components/schemas/Vehicle"),
                    new OA\Property(property: "quantity", type: "int", example: "124"),
                    new OA\Property(property: "supplyDate", type: "string", format: "date-time" ,example: "2025-03-28"),
                    new OA\Property(property: "purchasePrice", type: "string", example: "15000.00"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "supply ajouté avec succès",
                content: new OA\JsonContent(ref: "#/components/schemas/Supply")
            ),
            new OA\Response(
                response: 400, description: "Erreur de validation"
            )
        ]
    )]
    public function addSupplyr(
        Request $request,
        SerializerInterface $serializer,
        SupplyRepository $supplyRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $supply = $serializer->deserialize(
            $request->getContent(),
            Supplier::class,
            'json'
        );

        $entityManager->persist($supply);
        $entityManager->flush();
        return new JsonResponse(
            $serializer->serialize($supply, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );
    }

    #[Route('/api/supply/{id}', name: 'get_supply', methods: ['GET'])]
    #[OA\Get(
        summary: "Récupérer un supply par ID",
        description: "Retourne un supply en fonction de son ID",
        tags: ["Supply"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID de la supply"
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "supply trouvé",
                content: new OA\JsonContent(ref: "#/components/schemas/Supply")
            ),
            new OA\Response(
                response: 404,
                description: "sale non trouvé"
            )
        ]
    )]
    public function getSupplyById(
        int $id,
        SupplyRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse {
        $supply = $repository->find($id);

        if (!$supply) {
            return new JsonResponse(['message' => 'supply non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $jsonCustomer = $serializer->serialize($supply, 'json', ["groups" => "getAllSupply"]);
        return new JsonResponse($jsonCustomer, Response::HTTP_OK, [], true);
    }

    #[Route('/api/supply/{id}', name: 'delete_supply', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Supprimer un supply",
        description: "Supprime un supply en fonction de son ID",
        tags: ["Supply"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID due la supply à supprimer"
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "supply supprimé avec succès"
            ),
            new OA\Response(
                response: 404,
                description: "supply non trouvé"
            )
        ]
    )]
    public function deleteSupply(
        int $id,
        SupplyRepository $repository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $supply = $repository->find($id);

        if (!$supply) {
            return new JsonResponse(['message' => 'supply non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($supply);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
