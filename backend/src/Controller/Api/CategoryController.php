<?php

namespace App\Controller\Api;
//
use App\Entity\Category; 
use App\Repository\CategoryRepository;
//
use Doctrine\ORM\EntityManagerInterface;
//
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class CategoryController extends AbstractController
{
  #[Route('/api/category', methods: ['GET'])]
  public function index(CategoryRepository $categoryRepo): JsonResponse
  {
    $categories = $categoryRepo->findAll();

    return $this->json($categories, 200, [], ['groups' => ['category:read']]);
  }

  #[Route('/api/category', methods: ['POST'])]
  public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
  {

    // Validation
    $constraints = new Assert\Collection([
      'name' => [
        new Assert\NotBlank(),
        new Assert\Type('string')
      ],
      'slug' => [
        new Assert\NotBlank(),
        new Assert\Type('string')
      ],
    ]);
    
    // Format JSON
    $data = $request->toArray();

    // Validate & handle errors
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
    
    
    // Add to database
    // Grab data from request
    $categoryName = $data["name"];
    $categorySlug = $data["slug"];
    
    // Create new Category Instance
    $newCategory = new Category();
    $newCategory->setName($categoryName);
    $newCategory->setSlug($categorySlug);
    
    // persist
    $entityManager->persist($newCategory);
    
    // flush
    $entityManager->flush();
    
    // return response
    return $this->json($newCategory, Response::HTTP_CREATED, [], ["groups" => ["category:read"]]);
  }

  #[Route('/api/category/{id}', methods: ["PUT"])]
  public function edit(Category $category, EntityManagerInterface $entityManager, Request $request, SerializerInterface $serializer,
    ValidatorInterface $validator) {

    // deserialise data
  $serializer->deserialize($request->getContent(), Category::class, "json", ["object_to_populate" => $category]);

    
    $errors = $validator->validate($category);

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
    
    // Flush
    $entityManager->flush();

    return $this->json(
      $category,
      Response::HTTP_OK,
      [],
      ['groups' => ['category:read']]
    );
  
  }

  #[Route('/api/category/{id}', methods: ['DELETE'])]
  public function delete(Category $category, EntityManagerInterface $entityManager): JsonResponse {
    $entityManager->remove($category);
    $entityManager->flush();
    return $this->json(
      ['msg' => 'Successfully Deleted'],
      Response::HTTP_OK
    );
  }
}
