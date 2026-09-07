<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Entity\ProductVariant;
use App\Repository\ProductVariantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
// Validation
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Response;

final class OrdersController extends AbstractController
{
  #[Route('/api/order', methods: ["GET"])]
  public function index(): JsonResponse
  {
    $default = [
      "status" => "Ok",
    ];
    return $this->json($default, 200, [], []);
  }

  #[Route('/api/order', methods:["POST"])]
  public function create(
    Request $request,
    ValidatorInterface $validator,
    EntityManagerInterface $entityManager,
    ProductVariantRepository $productVariantRepository
    ){

    // Read JSON
    $data = $request->toArray();

    // Validation Rules (Maybe could store this somewhere else.)
    $constraints = new Assert\Collection([
      'customerName' => [
        new Assert\NotBlank(),
        new Assert\Type('string'),
      ],
      'customerEmail' => [
        new Assert\NotBlank(),
        new Assert\Email()
      ],
      'items' => [
        new Assert\NotBlank(),
        new Assert\Type('array'),
        new Assert\Count(min:1),
        new Assert\All([
          new Assert\Collection([
            'productVariantId' => [
              new Assert\NotBlank(),
              new Assert\Type('integer'),
              new Assert\Positive(),
            ],
            'quantity' => [
              new Assert\NotBlank(),
              new Assert\Type('integer'),
              new Assert\Positive()
            ],
          ])
        ])
      ]
    ]);

    // Validate the request data
    $errors = $validator->validate($data, $constraints);

    // if validation fails
    if (count($errors) > 0){
      $formattedErrors = [];
      foreach($errors as $error){
        $formattedErrors[] = [
          'field' => $error->getPropertyPath(),
          'message' => $error->getMessage()
        ];
      };
      return $this->json(['errors' => $formattedErrors], Response::HTTP_UNPROCESSABLE_ENTITY);
    };

    // Load Products
    // $productVariantRepo = $entityManager->getRepository(ProductVariantRepository::class);
    
    $customerName = $data['customerName'];
    $customerEmail = $data['customerEmail'];
    $orderItems = $data['items'];

    $isInStock = $productVariantRepository->checkStockAndPrices($orderItems);







    // Create Order
    $order = new Order();

  }


}
