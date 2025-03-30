<?php
namespace App\Controller;

use App\Entity\Reparation;
use App\Entity\Vehicle;
use App\Repository\ReparationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class ReparationController extends AbstractController
{
    #[Route('/api/reparation', name: 'reparation_list', methods: ['GET'])]
    #[OA\Get(
        path: "/api/reparation",
        summary: "Liste des reparations",
        tags: ["Reparation"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des reparations",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Reparation")

                )
            )
        ]
    )]
    public function getAllReparation(
        ReparationRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $reparation = $repository->findAll();
        $jsonCustomers = $serializer->serialize($reparation, 'json', ["groups" => "getAllReparation"]);

        return new JsonResponse($jsonCustomers, Response::HTTP_OK, [], true);
    }

    #[Route('/api/reparation', name: 'create_reparation', methods: ['POST'])]
    #[OA\Post(
        summary: "Ajouter une nouvelle reparation",
        description: "Ajoute une reparation en base de données",
        tags: ["Reparation"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                required: ["vehicleId","description","repairDate","cost",],
                properties: [
                    new OA\Property(property: "vehicleId", type: "integer", example: 1),
                    new OA\Property(property: "decription", type: "string", example: "John"),
                    new OA\Property(property: "repairDate", type: "string", example: "2021-10-10"),
                    new OA\Property(property: "cost", type: "decimal", example: 100.00)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "reparation ajouté avec succès",
                content: new OA\JsonContent(ref: "#/components/schemas/Reparation")
            ),
            new OA\Response(
                response: 400, description: "Erreur de validation"
            )
        ]
    )]
    public function addReparation(
        Request $request,
        SerializerInterface $serializer,
        ReparationRepository $reparationRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $reparation = $serializer->deserialize(
            $request->getContent(),
            Reparation::class,
            'json'
        );

        $entityManager->persist($reparation);
        $entityManager->flush();
        return new JsonResponse(
            $serializer->serialize($reparation, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );
    }

    #[Route('/api/reparation/{id}', name: 'get_reparation', methods: ['GET'])]
    #[OA\Get(
        summary: "Récupérer une reparation par ID",
        description: "Retourne une reparation en fonction de son ID",
        tags: ["Reparation"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID de la reparation"
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "reparation trouvé",
                content: new OA\JsonContent(ref: "#/components/schemas/Reparation")
            ),
            new OA\Response(
                response: 404,
                description: "reparation non trouvé"
            )
        ]
    )]
    public function getReparationById(
        int $id,
        ReparationRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse {
        $reparation = $repository->find($id);

        if (!$reparation) {
            return new JsonResponse(['message' => 'Reparation non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $jsonCustomer = $serializer->serialize($reparation, 'json', ["groups" => "getAllReparation"]);
        return new JsonResponse($jsonCustomer, Response::HTTP_OK, [], true);
    }

    #[Route('/api/reparation/{id}', name: 'delete_reparation', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Supprimer une reparation",
        description: "Supprime une reparation en fonction de son ID",
        tags: ["Reparation"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID due la reparation à supprimer"
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "reparation supprimé avec succès"
            ),
            new OA\Response(
                response: 404,
                description: "reparation non trouvé"
            )
        ]
    )]
    public function deleteReparation(
        int $id,
        ReparationRepository $repository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $reparation = $repository->find($id);

        if (!$reparation) {
            return new JsonResponse(['message' => 'reparation non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($reparation);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
