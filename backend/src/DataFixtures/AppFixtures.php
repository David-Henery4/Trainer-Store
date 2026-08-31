<?php

namespace App\DataFixtures;

use App\Entity\ProductVariant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use APP\Entity\Category;
use APP\Entity\Product;
use App\DataFixtures\Data\ProductFixtureData;
use App\DataFixtures\Data\CategoryFixtureData;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
      // Categories
      $categories = [];
    
    // This loops is like .entries
    // It loops to grab the key & value
    // First param is key. Second is values
    // here: $slug === key & $name === value
    // $categoryData as $slug => $name
    foreach (CategoryFixtureData::CATEGORIES as $slug => $name) {
      $category = new Category();
      $category->setName($name);
      $category->setSlug($slug);

      $manager->persist($category);

      $categories[$slug] = $category;
    }

    // Products
    $products = ProductFixtureData::PRODUCTS;

    foreach($products as $productData){
      $product = new Product();

      $product->setCategory(
        $categories[$productData['category']]
      );

      $product->setName($productData['name']);
      $product->setDescription($productData["description"]);
      $product->setBrand($productData["brand"]);
      $product->setImageUrl($productData["imageUrl"]);
      $product->setPrice($productData["price"]);
      $product->setSlug($productData["slug"]);
      $product->setIsActive($productData["isActive"]);

      $now = new \DateTimeImmutable();
      $product->setCreatedAt($now);
      $product->setUpdatedAt($now);

      $manager->persist($product);

      // Creating variants as seperate datbase table.
      // From the variants data in the products data.
      foreach($productData["variants"] as $variantsData){
        $variants = new ProductVariant();

        $variants->setProduct($product);
        $variants->setSize($variantsData["size"]);
        $variants->setStock($variantsData["stock"]);

        $manager->persist($variants);
      }

    }

        $manager->flush();
    }
}
