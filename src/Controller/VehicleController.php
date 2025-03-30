<?php
namespace App\Controller;

use App\Entity\Vehicle;
use App\Entity\Supplier;
use App\Repository\SupplyRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class VehicleController extends AbstractController
{
    #[Route('/api/vehicle', name: 'vehicle_list', methods: ['GET'])]
    #[OA\Get(
        path: "/api/vehicle",
        summary: "Liste des vehicle",
        tags: ["Vehicle"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des vehicles",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Vehicle")

                )
            )
        ]
    )]
    public function getAllVehicle(
        VehicleRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $vehicle = $repository->findAll();
        $jsonCustomers = $serializer->serialize($vehicle, 'json', ["groups" => "getAllVehicle"]);

        return new JsonResponse($jsonCustomers, Response::HTTP_OK, [], true);
    }

    #[Route('/api/vehicle', name: 'create_vehicle', methods: ['POST'])]
    #[OA\Post(
        summary: "Ajouter une nouvelle vehicle",
        description: "Ajoute une vehicle en base de données",
        tags: ["Vehicle"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                required: ["brand","model","year", "price", "mileage", "type", "fuelType", "transmissionType", "status"],
                properties: [
                    new OA\Property(property: "brand", type: "string", example: "Toyota"),
                    new OA\Property(property: "model", type: "string", example: "Yaris"),
                    new OA\Property(property: "year", type: "integer", example: 2024),
                    new OA\Property(property: "price", type: "number", format: "float", example: 1400.00),
                    new OA\Property(property: "mileage", type: "integer", example: 15000),
                    new OA\Property(property: "type", type: "string", enum: ["new", "used"], example: "New or used"),
                    new OA\Property(property: "fuelType", type: "string", enum: ["essence", "diesel", "électric", "hybride"], example: "hybride"),
                    new OA\Property(property: "transmissionType", type: "string", enum: ["manuelle", "automatique"], example: "automatique or manuelle"),
                    new OA\Property(property: "status", type: "string", enum: ["disponible", "vendu"], example: "disponible or vendu"),
                    new OA\Property(property: "createdAt", type: "string", format: "date-time", example: "2025-03-28T12:30:00Z"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "vehicle ajouté avec succès",
                content: new OA\JsonContent(ref: "#/components/schemas/Vehicle")
            ),
            new OA\Response(
                response: 400, description: "Erreur de validation"
            )
        ]
    )]
    public function addVehicle(
        Request $request,
        SerializerInterface $serializer,
        VehicleRepository $vehicleRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $vehicle = $serializer->deserialize(
            $request->getContent(),
            Supplier::class,
            'json'
        );

        $entityManager->persist($vehicle);
        $entityManager->flush();
        return new JsonResponse(
            $serializer->serialize($vehicle, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );
    }

    #[Route('/api/vehicle/{id}', name: 'get_vehicle', methods: ['GET'])]
    #[OA\Get(
        summary: "Récupérer un vehicle par ID",
        description: "Retourne un vehicle en fonction de son ID",
        tags: ["Vehicle"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID de la vehicle"
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "vehicle trouvé",
                content: new OA\JsonContent(ref: "#/components/schemas/Vehicle")
            ),
            new OA\Response(
                response: 404,
                description: "vehicle non trouvé"
            )
        ]
    )]
    public function getVehicleById(
        int $id,
        VehicleRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse {
        $vehicle = $repository->find($id);

        if (!$vehicle) {
            return new JsonResponse(['message' => 'vehicle non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $jsonCustomer = $serializer->serialize($vehicle, 'json', ["groups" => "getAllVehicle"]);
        return new JsonResponse($jsonCustomer, Response::HTTP_OK, [], true);
    }

    #[Route('/api/vehicle/{id}', name: 'delete_vehicle', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Supprimer un vehicle",
        description: "Supprime un vehicle en fonction de son ID",
        tags: ["Vehicle"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID de l' vehicle à supprimer"
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "vehicle supprimé avec succès"
            ),
            new OA\Response(
                response: 404,
                description: "vehicle non trouvé"
            )
        ]
    )]
    public function deleteVehicle(
        int $id,
        VehicleRepository $repository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $vehicle = $repository->find($id);

        if (!$vehicle) {
            return new JsonResponse(['message' => 'vehicle non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($vehicle);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
