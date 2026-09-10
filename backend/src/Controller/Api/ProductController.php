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
  public function create(Request $request, EntityManagerInterface $entityManager){
    $data = $request->toArray();

    // Validation & Return Error if needed

    // Send to database
    $productName = $data["name"];
    $productBrand = $data["brand"];
    $productSlug = $data["slug"];
    $productDescription = $data["description"];
    $productPrice = $data["price"];
    $productImageUrl = $data["image_url"];
    $productisActive = $data["is_active"];
    //
    $productCategoryId = $data["category_id"];
    //
    $newProduct = new Product();
    //
    $newProduct->setName($productName);
    $newProduct->setPrice($productPrice);
    $newProduct->setBrand($productBrand);
    $newProduct->setSlug($productSlug);
    $newProduct->setDescription($productDescription);
    $newProduct->setImageUrl($productImageUrl);
    $newProduct->setIsActive($productisActive);
    //
    $newProduct->setCreatedAt(new \DateTimeImmutable());
    // $newProduct->setUpdatedAt($productupdatedAt);
    //
    $entityManager->persist($newProduct);
    //
    $entityManager->flush();


    // Return Success msg

  }

  #[Route("/api/products/{id}", methods: ["PATCH"])]
  public function update(Request $request) {
    
  }
  
  #[Route('/api/products/{id}', methods: ["DELETE"])]
  public function delete(Request $request) {
    
  }
}
