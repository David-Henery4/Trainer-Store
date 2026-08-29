<?php

namespace App\DataFixtures\Data;

final class ProductFixtureData
{
  public const PRODUCTS = [
    [
      'category' => 'running',
      'brand' => 'Nike',
      'name' => 'Pegasus 41',
      'slug' => 'nike-pegasus-41',
      'description' => 'Responsive everyday running trainer.',
      'price' => '129.99',
      'imageUrl' => '/images/products/nike-pegasus-41.jpg',
      'isActive' => true,
      'variants' => [
        ['size' => '7', 'stock' => 4],
        ['size' => '8', 'stock' => 6],
        ['size' => '9', 'stock' => 2],
        ['size' => '10', 'stock' => 0],
      ],
    ],
    [
      'category' => 'lifestyle',
      'brand' => 'Nike',
      'name' => 'Air Max 90',
      'slug' => 'nike-air-max-90',
      'description' => 'Classic everyday lifestyle trainer.',
      'price' => '139.99',
      'imageUrl' => '/images/products/nike-air-max-90.jpg',
      'isActive' => true,
      'variants' => [
        ['size' => '7', 'stock' => 3],
        ['size' => '8', 'stock' => 5],
        ['size' => '9', 'stock' => 1],
      ],
    ],
    [
      'category' => 'running',
      'brand' => 'Adidas',
      'name' => 'Ultraboost 5',
      'slug' => 'adidas-ultraboost-5',
      'description' => 'Cushioned trainer designed for everyday running.',
      'price' => '159.99',
      'imageUrl' => '/images/products/adidas-ultraboost-5.jpg',
      'isActive' => true,
      'variants' => [
        ['size' => '7', 'stock' => 5],
        ['size' => '8', 'stock' => 4],
        ['size' => '9', 'stock' => 3],
      ],
    ],
  ];
}
