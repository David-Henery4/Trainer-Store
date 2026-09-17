<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateOrderRequest {
  #[Assert\NotBlank]
  #[Assert\Type('string')]
  public ?string $customerName = null;

  #[Assert\NotBlank]
  #[Assert\Email]
  public ?string $customerEmail = null;

  #[Assert\NotBlank]
  #[Assert\Type('array')]
  #[Assert\Count(min: 1)]
  #[Assert\All([
    new Assert\Collection([
      'productVariantId' => [
        new Assert\NotBlank(),
        new Assert\Type('integer'),
        new Assert\Positive(),
      ],
      'quantity' => [
        new Assert\NotBlank(),
        new Assert\Type('integer'),
        new Assert\Positive(),
      ],
    ]),
  ])]
  public ?array $items = null;
}