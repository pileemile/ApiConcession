<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/users')]
final class UserController extends AbstractController
{
    #[Route('', name: 'user_list', methods: ['GET'])]
    #[OA\Get(
        summary: "Liste des utilisateurs",
        tags: ["User"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Liste des utilisateurs",
                content: new OA\JsonContent(type: "array", items: new OA\Items(ref: "#/components/schemas/User"))
            )
        ]
    )]
    public function getAllUsers(
        UserRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse
    {
        $users = $repository->findAll();
        $jsonUsers = $serializer->serialize($users, 'json', ["groups" => "getAllUsers"]);

        return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }

    #[Route('', name: 'add_user', methods: ['POST'])]
    #[OA\Post(
        summary: "Ajouter un utilisateur",
        tags: ["User"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: "object",
                required: ["email", "password", "roles"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "test@example.com"),
                    new OA\Property(property: "password", type: "string", example: "password123"),
                    new OA\Property(property: "roles", type: "array", items: new OA\Items(type: "string"), example: ["ROLE_USER"]),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Utilisateur ajouté avec succès"),
            new OA\Response(response: 400, description: "Erreur de validation")
        ]
    )]
    public function addUser(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['password'], $data['roles'])) {
            return $this->json(['message' => 'Données invalides'], 400);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setRoles($data['roles']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse($serializer->serialize($user, 'json'), Response::HTTP_CREATED, [], true);
    }

    #[Route('/{id}', name: 'get_user', methods: ['GET'])]
    #[OA\Get(
        summary: "Récupérer un utilisateur par ID",
        tags: ["User"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 200, description: "Utilisateur trouvé"),
            new OA\Response(response: 404, description: "Utilisateur non trouvé")
        ]
    )]
    public function getUserById(int $id, UserRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $user = $repository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $jsonUser = $serializer->serialize($user, 'json', ["groups" => "getAllUsers"]);
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'delete_user', methods: ['DELETE'])]
    #[OA\Delete(
        summary: "Supprimer un utilisateur",
        tags: ["User"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(response: 204, description: "Utilisateur supprimé"),
            new OA\Response(response: 404, description: "Utilisateur non trouvé")
        ]
    )]
    public function deleteUser(int $id, UserRepository $repository, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = $repository->find($id);

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
