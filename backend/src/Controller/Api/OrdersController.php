<?php

namespace App\Controller\Api;

use App\Entity\Order;
use App\Entity\OrderItem;
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

  #[Route('/api/order', methods: ["POST"])]
  public function create(
    Request $request,
    ValidatorInterface $validator,
    EntityManagerInterface $entityManager,
    ProductVariantRepository $productVariantRepository
  ) {

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
        new Assert\Count(min: 1),
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
    if (count($errors) > 0) {
      $formattedErrors = [];
      foreach ($errors as $error) {
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

    $productCheck = $productVariantRepository->checkStockAndPrices($orderItems);
    // return [
    // 'isInStock' => $isInStock,
    // 'total' => $total,
    // 'items' => $checkedItems
    // ];

    if (!$productCheck['isInStock']) {
      return $this->json([
        'error' => 'One or more products are out of stock'
      ], 409);
    }

    // Create Order
    $order = new Order();
    $order->setCustomerName($customerName);
    $order->setCustomerEmail($customerEmail);
    $order->setTotal($productCheck['total']);
    $order->setStatus("Pending");
    $order->setCreatedAt(new \DateTimeImmutable());
    $entityManager->persist($order);

    // Create OrderItem

    // Using the 'product_id' in the variant table, to get the product from
    // the product table that matches the id
    // Docterine uses the getter function from the entity, under the hood,
    // to grab the prouduct we need, because the 'product_id' has a relationship
    // to the product with the same id in the products table.
    // Saves us having to call the product again seperatly for extra info.
    // Doctrine does not use your getProduct() method to discover the relationship. 
    // Doctrine already knows about the relationship because of the mapping in the entity.
    // Doctrine already knows what product to get from the products table, using
    // the 'product_id' in the productVariant table. We can then us that to get
    // the information in the product table using the getter method in the 
    // productVariant entity.
    // One extra detail: depending on how the relationship is configured, Doctrine may lazy-load the Product only when you actually access it. So sometimes the related product isn’t fully fetched until getProduct() is used. But the relationship itself comes from the Doctrine mapping, not from the getter.
    // Im now getting the product info from the variant in the checkStockAndPrices($orderItems) function.



    foreach ($productCheck['items'] as $requestedItem) {

      // Grabing the variant
      // variant only needed if being included in the return.
      // $variant = $productCheck['variant'];
      $product = $requestedItem['product'];

      // Now can use this to access product entity
      // Getters & setters!
      // $product = $variant->getProduct(); 
      $orderItem = new OrderItem();

      $orderItem->setParentOrder($order);
      $orderItem->setProduct($product);
      $orderItem->setProductName($product->getName());
      $orderItem->setUnitPrice($requestedItem['unitPrice']);
      $orderItem->setQuantity($requestedItem['quantity']);

      $order->addItem($orderItem);
      $entityManager->persist($orderItem);
    }

    // Save to database & send reply

    $entityManager->flush();
    return $this->json($order, Response::HTTP_CREATED,
      [],
      ['groups' => ['order:read']]);
  }
}
