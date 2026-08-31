<?php

namespace App\Controller\Api;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class ProductController extends AbstractController
{
  #[Route('/api/products', methods: ['GET'])]
    public function index(EntityManagerInterface $enity, SerializerInterface $serializer): JsonResponse
    {

    $products = $enity->getRepository(Product::class)->findAll();

    $json_content = $serializer->serialize($products, "json", ['groups' => ['product:read']]);

    return JsonResponse::fromJsonString($json_content);
    
    }
}
