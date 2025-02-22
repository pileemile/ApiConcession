<?php

namespace App\Controller;

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
    #[Route('/customer', name: 'customer.getAll', methods: ['GET'])]
    public function getAllCustomers(
        CustomerRepository $repository,
        SerializerInterface $serializer
    ): JsonResponse {
        $customers = $repository->findAll();

        $jsonCustomers = $serializer->serialize($customers, 'json', ["groups" => "getAllCustomers"]);

        return new JsonResponse(
            $jsonCustomers,
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/customer', name: 'customer.add', methods: ['POST'])]
    public function addCustomer(
        Request $request,
        SerializerInterface $serializer,
        CustomerRepository $customerRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $customer = $serializer->deserialize(
            $request->getContent(),
            Customer::class,
            'json'
        );

        $customerRepository->save($customer, true);

        return new JsonResponse(
            $customer,
            Response::HTTP_CREATED,
            [],
            true
        );
    }

}
