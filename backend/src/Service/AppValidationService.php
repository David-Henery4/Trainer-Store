<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiValidationService
{
  public function __construct(
    private ValidatorInterface $validator
  ) {}

  public function validate(object $object): ?JsonResponse
  {
    $errors = $this->validator->validate($object);

    if (count($errors) === 0) {
      return null;
    }

    $formattedErrors = [];

    foreach ($errors as $error) {
      $field = $error->getPropertyPath();

      $formattedErrors[$field][] = $error->getMessage();
    }

    return new JsonResponse(
      ['errors' => $formattedErrors],
      Response::HTTP_UNPROCESSABLE_ENTITY
    );
  }
}
