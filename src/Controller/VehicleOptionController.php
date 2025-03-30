<?php
namespace App\Controller;

use App\Entity\Supplier;
use App\Repository\VehicleOptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class VehicleOptionController extends AbstractController
{
    #[Route('/api/vehicle_option', name: 'vehicle_option_list', methods: ['GET'])]
    #[OA\Get(
        path: "/api/vehicle_option",
        summary: "Liste des vehicle_option",
        tags: ["VehicleOption"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des vehicle_option",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/VehicleOption")

                )
            )
        ]
    )]
    public function getAllVehicleOption(
        VehicleOptionRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $vehicleOption = $repository->findAll();
        $jsonCustomers = $serializer->serialize($vehicleOption, 'json', ["groups" => "getAllVehicleOption"]);

        return new JsonResponse($jsonCustomers, Response::HTTP_OK, [], true);
    }

    #[Route('/api/vehicle_option', name: 'create_vehicle_option', methods: ['POST'])]
    #[OA\Post(
        summary: "Ajouter une nouvelle vehicle_option",
        description: "Ajoute une vehicle_option en base de données",
        tags: ["VehicleOption"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                required: ["options"],
                properties: [
                    new OA\Property(property: "options",ref: "#/components/schemas/Supplier"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "vehicleOption ajouté avec succès",
                content: new OA\JsonContent(ref: "#/components/schemas/VehicleOption")
            ),
            new OA\Response(
                response: 400, description: "Erreur de validation"
            )
        ]
    )]
    public function addVehicleOption(
        Request $request,
        SerializerInterface $serializer,
        VehicleOptionRepository $vehicleRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $vehicleOption = $serializer->deserialize(
            $request->getContent(),
            Supplier::class,
            'json'
        );

        $entityManager->persist($vehicleOption);
        $entityManager->flush();
        return new JsonResponse(
            $serializer->serialize($vehicleOption, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );
    }

    #[Route('/api/vehicleOption/{id}', name: 'get_vehicleOption', methods: ['GET'])]
    #[OA\Get(
        summary: "Récupérer un vehicleOption par ID",
        description: "Retourne un vehicleOption en fonction de son ID",
        tags: ["VehicleOption"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID de la vehicleOption"
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "vehicle trouvé",
                content: new OA\JsonContent(ref: "#/components/schemas/VehicleOption")
            ),
            new OA\Response(
                response: 404,
                description: "vehicle non trouvé"
            )
        ]
    )]
    public function getVehicleOptionById(
        int $id,
        VehicleOptionRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse {
        $vehicleOption = $repository->find($id);

        if (!$vehicleOption) {
            return new JsonResponse(['message' => 'vehicleOption non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $jsonCustomer = $serializer->serialize($vehicleOption, 'json', ["groups" => "getAllVehicleOption"]);
        return new JsonResponse($jsonCustomer, Response::HTTP_OK, [], true);
    }

    #[Route('/api/vehicleOption/{id}', name: 'delete_vehicleOption', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Supprimer un vehicleOption",
        description: "Supprime un vehicleOption en fonction de son ID",
        tags: ["VehicleOption"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID de l' vehicleOption à supprimer"
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "vehicleOption supprimé avec succès"
            ),
            new OA\Response(
                response: 404,
                description: "vehicleOption non trouvé"
            )
        ]
    )]
    public function deleteVehicleOption(
        int $id,
        VehicleOptionRepository $repository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $vehicleOption = $repository->find($id);

        if (!$vehicleOption) {
            return new JsonResponse(['message' => 'vehicleOption non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($vehicleOption);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
