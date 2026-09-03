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
}
