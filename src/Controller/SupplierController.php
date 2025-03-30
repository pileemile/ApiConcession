<?php
namespace App\Controller;

use App\Entity\Supplier;
use App\Repository\SupplierRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

class SupplierController extends AbstractController
{
    #[Route('/api/supplier', name: 'supplier_list', methods: ['GET'])]
    #[OA\Get(
        path: "/api/supplier",
        summary: "Liste des suppliers",
        tags: ["Supplier"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des suppliers",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(ref: "#/components/schemas/Supplier")

                )
            )
        ]
    )]
    public function getAllSupplier(
        SupplierRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $supplier = $repository->findAll();
        $jsonCustomers = $serializer->serialize($supplier, 'json', ["groups" => "gatAllSupplier"]);

        return new JsonResponse($jsonCustomers, Response::HTTP_OK, [], true);
    }

    #[Route('/api/supplier', name: 'create_supplier', methods: ['POST'])]
    #[OA\Post(
        summary: "Ajouter une nouvelle supplier",
        description: "Ajoute une supplier en base de données",
        tags: ["Supplier"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                required: ["name","contact","adress","email","phone"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "John Doe"),
                    new OA\Property(property: "contact", type: "string", example: "02 12 21"),
                    new OA\Property(property: "adress", type: "string", example: "12 rue de la paix"),
                    new OA\Property(property: "email", type: "string", example: "@gmail.com"),
                    new OA\Property(property: "phone", type: "string", example: "02 15 85 12"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Supplier ajouté avec succès",
                content: new OA\JsonContent(ref: "#/components/schemas/Supplier")
            ),
            new OA\Response(
                response: 400, description: "Erreur de validation"
            )
        ]
    )]
    public function addSupplier(
        Request $request,
        SerializerInterface $serializer,
        SupplierRepository $supplierRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $supplier = $serializer->deserialize(
            $request->getContent(),
            Supplier::class,
            'json'
        );

        $entityManager->persist($supplier);
        $entityManager->flush();
        return new JsonResponse(
            $serializer->serialize($supplier, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );
    }

    #[Route('/api/supplier/{id}', name: 'get_supplier', methods: ['GET'])]
    #[OA\Get(
        summary: "Récupérer un supplier par ID",
        description: "Retourne un supplier en fonction de son ID",
        tags: ["Supplier"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID de la supplier"
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "supplier trouvé",
                content: new OA\JsonContent(ref: "#/components/schemas/Supplier")
            ),
            new OA\Response(
                response: 404,
                description: "sale non trouvé"
            )
        ]
    )]
    public function getSupplierById(
        int $id,
        SupplierRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse {
        $supplier = $repository->find($id);

        if (!$supplier) {
            return new JsonResponse(['message' => 'Supplier non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $jsonCustomer = $serializer->serialize($supplier, 'json', ["groups" => "gatAllSupplier"]);
        return new JsonResponse($jsonCustomer, Response::HTTP_OK, [], true);
    }

    #[Route('/api/supplier/{id}', name: 'delete_supplier', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Supprimer un supplier",
        description: "Supprime un supplier en fonction de son ID",
        tags: ["Supplier"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer"),
                description: "ID due la supplier à supprimer"
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "supplier supprimé avec succès"
            ),
            new OA\Response(
                response: 404,
                description: "supplier non trouvé"
            )
        ]
    )]
    public function deleteSupplier(
        int $id,
        SupplierRepository $repository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $supplier = $repository->find($id);

        if (!$supplier) {
            return new JsonResponse(['message' => 'supplier non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($supplier);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
