<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateProductVariantRequest {
  #[Assert\NotBlank(
    message: 'Size is required.'
  )]
  public ?string $size = null;

  #[Assert\NotNull(
    message: 'Stock quantity is required.'
  )]
  #[Assert\PositiveOrZero(
    message: 'Stock quantity must be zero or greater.'
  )]
  public ?int $stock = null;
}