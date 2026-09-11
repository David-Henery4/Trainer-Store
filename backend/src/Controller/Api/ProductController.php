<?php

namespace App\Controller\Api;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\CategoryRepository;
use App\DTO\CreateProductRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ProductController extends AbstractController
{
  #[Route('/api/products', methods: ['GET'])]
  public function index(ProductRepository $repository, Request $request): JsonResponse
  {

    // Would need: SerializerInterface $serializer | in params
    // $products = $enity->getRepository(Product::class)->findAll();
    // $json_content = $serializer->serialize($products, "json", ['groups' => ['product:read']]);
    // return JsonResponse::fromJsonString($json_content);

    // Could also do it this way. Would need: EntityManagerInterface $enity | in params.
    // $products = $enity->getRepository(Product::class)->findFilteredAndSorted(
    //   $category,
    //   $size,
    //   $sort,
    //   $order);

    // dd($request->query->get('order'));

    $category = $request->query->get('category');
    $size = $request->query->get('size');
    $sort = $request->query->get('sort', 'name');
    $order = $request->query->get('order', 'asc');

    $products = $repository->findFilteredAndSorted($category, $size, $sort, $order);

    return $this->json(
      $products,
      200,
      [],
      ['groups' => ['product:read']]
    );
  }

  #[Route('/api/products/{id}', methods: ['GET'])]
  public function show(Product $product): JsonResponse
  {
    return $this->json($product, 200, [], ['groups' => ['product:read']]);
  }

  // Admin only functions
  #[Route('/api/products', methods: ["POST"])]
  public function create(
    Request $request,
    EntityManagerInterface $entityManager,
    CategoryRepository $categoryRepository,
    SerializerInterface $serializer,
    ValidatorInterface $validator,
  ): JsonResponse {

    /** @var CreateProductRequest $productRequest */
    $productRequest = $serializer->deserialize(
      $request->getContent(),
      CreateProductRequest::class,
      'json'
    );

    $errors = $validator->validate($productRequest);
    if (count($errors) > 0) {
      $formattedErrors = [];

      foreach ($errors as $error) {
        $field = $error->getPropertyPath();

        $formattedErrors[$field][] = $error->getMessage();
      }

      return $this->json(
        ['errors' => $formattedErrors],
        Response::HTTP_UNPROCESSABLE_ENTITY
      );
    }

    // Get & check category
    $category = $categoryRepository->find(
      $productRequest->categoryId
    );

    if (!$category) {
      return $this->json(
        [
          'errors' => [
            'category_id' => [
              'The selected category does not exist.'
            ]
          ]
        ],
        Response::HTTP_UNPROCESSABLE_ENTITY
      );
    }

    // Send to database
    $newProduct = new Product();
    //
    $newProduct->setName($productRequest->name);
    $newProduct->setBrand($productRequest->brand);
    $newProduct->setSlug($productRequest->slug);
    $newProduct->setDescription($productRequest->description);
    $newProduct->setPrice($productRequest->price);
    $newProduct->setImageUrl($productRequest->imageUrl);
    $newProduct->setIsActive($productRequest->isActive);
    $newProduct->setCategory($category);
    //
    $newProduct->setCreatedAt(new \DateTimeImmutable());
    $newProduct->setUpdatedAt(new \DateTimeImmutable());
    //
    $entityManager->persist($newProduct);
    //
    $entityManager->flush();


    // Return Success msg
    return $this->json(
      $newProduct,
      Response::HTTP_CREATED,
      [],
      ['groups' => ['product:read']]
    );
  }

  #[Route("/api/products/{id}", methods: ["PATCH"])]
  public function update(Request $request) {}

  #[Route('/api/products/{id}', methods: ["DELETE"])]
  public function delete(Request $request) {}
}
