<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use App\Repository\SellerRepository;
use App\Entity\Seller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api/sellers')]
final class SellerController extends AbstractController
{
    #[Route('', name: 'seller_list', methods: ['GET'])]
    #[OA\Get(
        path: "/api/sellers",
        summary: "Liste des vendeurs",
        tags: ["Seller"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des vendeurs",
                content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/Seller"))
            )
        ]
    )]
    public function getAllSellers(
        SellerRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse {
        $sellers = $repository->findAll();
        $jsonSellers = $serializer->serialize($sellers, 'json', ["groups" => "getAllSellers"]);

        return new JsonResponse($jsonSellers, Response::HTTP_OK, [], true);
    }

    #[Route('', name: 'add_seller', methods: ['POST'])]
    #[OA\Post(
        summary: "Ajouter un vendeur",
        tags: ["Seller"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                required: ["name", "prenom", "email", "telephone", "dateEmbauche"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Dupont"),
                    new OA\Property(property: "prenom", type: "string", example: "Jean"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "jean.dupont@example.com"),
                    new OA\Property(property: "telephone", type: "string", example: "0601020304"),
                    new OA\Property(property: "dateEmbauche", type: "string", format: "date-time", example: "2025-03-23T10:00:00Z"),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Vendeur ajouté avec succès"),
            new OA\Response(response: 400, description: "Erreur de validation")
        ]
    )]
    public function addSeller(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $seller = $serializer->deserialize($request->getContent(), Seller::class, 'json');

        $entityManager->persist($seller);
        $entityManager->flush();

        return new JsonResponse(
            $serializer->serialize($seller, 'json'),
            Response::HTTP_CREATED,
            [],
            true
        );
    }

    #[Route('/{id}', name: 'get_seller', methods: ['GET'])]
    #[OA\Get(
        summary: "Récupérer un vendeur par ID",
        tags: ["Seller"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Vendeur trouvé"),
            new OA\Response(response: 404, description: "Vendeur non trouvé")
        ]
    )]
    public function getSellerById(
        int $id,
        SellerRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse {
        $seller = $repository->find($id);

        if (!$seller) {
            return new JsonResponse(['message' => 'Vendeur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $jsonSeller = $serializer->serialize($seller, 'json', ["groups" => "getAllSellers"]);
        return new JsonResponse($jsonSeller, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'delete_seller', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Supprimer un vendeur",
        tags: ["Seller"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 204, description: "Vendeur supprimé"),
            new OA\Response(response: 404, description: "Vendeur non trouvé")
        ]
    )]
    public function deleteSeller(
        int $id,
        SellerRepository $repository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $seller = $repository->find($id);

        if (!$seller) {
            return new JsonResponse(['message' => 'Vendeur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($seller);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
