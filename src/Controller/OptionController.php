<?php
namespace App\Controller;

use App\Entity\Option;
use App\Entity\VehicleOption;
use App\Repository\OptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class OptionController extends AbstractController
{
    #[Route('/api/option', name: 'option_list', methods: ['GET'])]
    #[OA\Get(
        path: "/api/option",
        summary: "Liste des options",
        tags: ["Option"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des options",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Option")

                )
            )
        ]
    )]
    public function getAllOption(
        OptionRepository    $repository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $option = $repository->createQueryBuilder('o')
            ->leftJoin('o.vehicleOptions', 'vo')
            ->addSelect('vo')
            ->getQuery()
            ->getResult();
        $jsonCustomers = $serializer->serialize($option, 'json', ["groups" => "getAllOption"]);

        return new JsonResponse($jsonCustomers, Response::HTTP_OK, [], true);
    }

    #[Route('/api/options', name: 'create_option', methods: ['POST'])]
    #[OA\Post(
        summary: "Ajouter une nouvelle option",
        description: "Ajoute une option en base de données",
        tags: ["Option"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                required: ["name","vehicleOptions"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "John"),
                    new OA\Property(property: "vehicleOptions", ref: "#/components/schemas/VehicleOption"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Option ajouté avec succès",
                content: new OA\JsonContent(ref: "#/components/schemas/Option")
            ),
            new OA\Response(
                response: 400, description: "Erreur de validation"
            )
        ]
    )]
    public function addOption(Request $request, EntityManagerInterface $manager): JsonResponse
    {
        // Décoder les données envoyées
        $data = json_decode($request->getContent(), true);

        // Vérification de la présence du champ "name"
        if (!isset($data['name']) || empty($data['name'])) {
            return $this->json(['error' => 'Le champ "name" est obligatoire'], 400);
        }

        // Créer une nouvelle Option
        $option = new Option();
        $option->setName($data['name']);

        // Ajouter les VehicleOption associés si des IDs sont fournis
        if (isset($data['vehicleOptionIds']) && is_array($data['vehicleOptionIds'])) {
            foreach ($data['vehicleOptionIds'] as $vehicleOptionId) {
                $vehicleOption = $manager->getRepository(VehicleOption::class)->find($vehicleOptionId);
                if ($vehicleOption) {
                    $option->addVehicleOption($vehicleOption); // Lier l'option à la VehicleOption
                }
            }
        }

        // Persister l'option dans la base de données
        $manager->persist($option);
        $manager->flush();

        // Retourner la réponse avec l'option créée
        return $this->json($option, 201);
    }

    #[Route('/api/options/{id}', name: 'get_option', methods: ['GET'])]
    #[OA\Get(
        summary: "Récupérer une option par ID",
        description: "Retourne une option en fonction de son ID",
        tags: ["Option"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID de l'option"
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Option trouvé",
                content: new OA\JsonContent(ref: "#/components/schemas/Option")
            ),
            new OA\Response(
                response: 404,
                description: "Option non trouvé"
            )
        ]
    )]
    public function getOptionById(
        int $id,
        OptionRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse {
        $option = $repository->find($id);

        if (!$option) {
            return new JsonResponse(['message' => 'Option non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $jsonCustomer = $serializer->serialize($option, 'json', ["groups" => "getAllOption"]);
        return new JsonResponse($jsonCustomer, Response::HTTP_OK, [], true);
    }

    #[Route('/api/options/{id}', name: 'delete_option', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Supprimer une option",
        description: "Supprime une option en fonction de son ID",
        tags: ["Option"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID due l'option à supprimer"
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "Option supprimé avec succès"
            ),
            new OA\Response(
                response: 404,
                description: "Option non trouvé"
            )
        ]
    )]
    public function deleteOption(
        int $id,
        OptionRepository $repository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $option = $repository->find($id);

        if (!$option) {
            return new JsonResponse(['message' => 'Option non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($option);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}


