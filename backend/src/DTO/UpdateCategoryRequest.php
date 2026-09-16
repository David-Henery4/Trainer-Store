<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateCategoryRequest {
  #[Assert\NotBlank(message: 'Category name is required.')]
  #[Assert\Length(
    max: 180,
    maxMessage: 'Category name cannot be longer than 180 characters.'
  )]
  public ?string $name = null;

  #[Assert\NotBlank(message: 'Category slug is required.')]
  #[Assert\Length(
    max: 180,
    maxMessage: 'Category slug cannot be longer than 180 characters.'
  )]
  public ?string $slug = null;
};