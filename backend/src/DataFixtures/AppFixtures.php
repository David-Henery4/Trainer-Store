<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use APP\Entity\Category;
use App\DataFixtures\Data\ProductFixtureData;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
      // Categories
      $categories = [];

      $categoryData = [
        "running" => "Running",
        "lifestyle" => "Lifestyle",
        "basketball" => "Basketball",
        "training" => "Training",
      ];

    foreach ($categoryData as $slug => $name) {
      $category = new Category();
      $category->setName($name);
      $category->setSlug($slug);

      $manager->persist($category);

      $categories[$slug] = $category;
    }

    // Products
    $products = ProductFixtureData::PRODUCTS;

      // ProductVariants

        $manager->flush();
    }
}
