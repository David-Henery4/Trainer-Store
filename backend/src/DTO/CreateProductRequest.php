<?php

namespace App\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class CreateProductRequest
{
  #[Assert\NotBlank(message: 'Product name is required.')]
  #[Assert\Length(
    max: 180,
    maxMessage: 'Product name cannot be longer than 180 characters.'
  )]
  public ?string $name = null;


  #[Assert\NotBlank(message: 'Brand is required.')]
  #[Assert\Length(
    max: 180,
    maxMessage: 'Brand cannot be longer than 180 characters.'
  )]
  public ?string $brand = null;


  #[Assert\NotBlank(message: 'Slug is required.')]
  #[Assert\Length(max: 180)]
  public ?string $slug = null;


  #[Assert\NotBlank(message: 'Description is required.')]
  public ?string $description = null;


  #[Assert\NotBlank(message: 'Price is required.')]
  #[Assert\Positive(message: 'Price must be greater than zero.')]
  public ?string $price = null;


  #[SerializedName('image_url')]
  #[Assert\Url(message: 'Image URL must be a valid URL.')]
  public ?string $imageUrl = null;


  #[SerializedName('is_active')]
  #[Assert\NotNull(message: 'Active status is required.')]
  public ?bool $isActive = null;


  #[SerializedName('category_id')]
  #[Assert\NotNull(message: 'Category is required.')]
  #[Assert\Positive(message: 'Category ID must be valid.')]
  public ?int $categoryId = null;
}
