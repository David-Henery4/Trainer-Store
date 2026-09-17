<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Entity\ProductVariant;
use App\DTO\CreateProductVariantRequest;
use App\DTO\UpdateProductVariantRequest;
//
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
//
use Doctrine\ORM\EntityManagerInterface;

final class ProductVariantController extends AbstractController
{
  #[Route('/api/products/variants/{id}', methods: ['GET'])]
  public function index() {}

  #[Route('/api/products/variants/{id}', methods: ['POST'])]
  public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, Product $product, EntityManagerInterface $em): JsonResponse
  {
    // {id} is product id
    $variantRequest = $serializer->deserialize($request->getContent(), CreateProductVariantRequest::class, 'json');
    //
    $errors = $validator->validate($variantRequest);
    //
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
    //
    $variant = new ProductVariant();
    $variant->setSize($variantRequest->size);
    $variant->setStock($variantRequest->stock);
    $product->addVariant($variant);
    //
    $em->persist($variant);
    $em->flush();
    //
    return $this->json(
      [
        'message' => 'Product variant created successfully.',
        'variantId' => $variant->getId()
      ],
      Response::HTTP_CREATED
    );
  }

  #[Route('/api/products/variants/{id}', methods: ['PUT'])]
  public function edit(
    // Product $product,
    ProductVariant $variant,
    Request $request,
    EntityManagerInterface $entityManager,
    SerializerInterface $serializer,
    ValidatorInterface $validator
  ) {
    // {id} is productVariant id
    $productVariantRequest = $serializer->deserialize(
      $request->getContent(),
      UpdateProductVariantRequest::class,
      'json'
    );
    //
    $errors = $validator->validate($productVariantRequest);
    //
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
    //
    $variant->setSize($productVariantRequest->size);
    $variant->setStock($productVariantRequest->stock);
    // Would only need this when moving variant to a different product.
    // $product->addVariant($variant);
    //
    $entityManager->flush();
    return $this->json(
      [
        'message' => 'Product variant updated successfully.',
        'variantId' => $variant->getId()
      ],
      Response::HTTP_CREATED
    );
  }

  #[Route('/api/products/variants/{id}', methods: ['DELETE'])]
  public function delete(ProductVariant $variant, EntityManagerInterface $entityManager): JsonResponse {
    // {id} is productVariant id
    $entityManager->remove($variant);
    $entityManager->flush();
    return $this->json(
      ['msg' => 'Successfully Deleted'],
      Response::HTTP_OK
    );
  }
}
