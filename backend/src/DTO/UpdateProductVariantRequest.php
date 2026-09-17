<?php 

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateProductVariantRequest {
  #[Assert\NotBlank]
  public ?string $size = null;

  #[Assert\NotNull]
  #[Assert\PositiveOrZero]
  public ?int $stock = null;
}