<?php

namespace App\Repository;

use App\Entity\ProductVariant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductVariant>
 */
class ProductVariantRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, ProductVariant::class);
  }

  public function checkStockAndPrices(array $orderItems): array
  {
    $variantIds = array_column($orderItems, 'productVariantId');

    $qb = $this->createQueryBuilder('pv');

    $variants = $qb
      ->where($qb->expr()->in('pv.id', ':variantIds'))
      ->setParameter('variantIds', $variantIds)
      ->getQuery()
      ->getResult();

    $variantsById = [];

    foreach ($variants as $variant) {
      $variantsById[$variant->getId()] = $variant;
    }

    $isInStock = true;
    $total = 0;

    foreach ($orderItems as $orderItem) {
      $variantId = $orderItem['productVariantId'];
      $quantity = $orderItem['quantity'];

      if (!isset($variantsById[$variantId])) {
        return [
          'isInStock' => false,
          'total' => 0,
        ];
      }

      $variant = $variantsById[$variantId];

      if ($variant->getStock() < $quantity) {
        $isInStock = false;
      }

      $total += $variant->getPrice() * $quantity;
    }

    return [
      'isInStock' => $isInStock,
      'total' => $total,
    ];
  }

  //    /**
  //     * @return ProductVariant[] Returns an array of ProductVariant objects
  //     */
  //    public function findByExampleField($value): array
  //    {
  //        return $this->createQueryBuilder('p')
  //            ->andWhere('p.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->orderBy('p.id', 'ASC')
  //            ->setMaxResults(10)
  //            ->getQuery()
  //            ->getResult()
  //        ;
  //    }

  //    public function findOneBySomeField($value): ?ProductVariant
  //    {
  //        return $this->createQueryBuilder('p')
  //            ->andWhere('p.exampleField = :val')
  //            ->setParameter('val', $value)
  //            ->getQuery()
  //            ->getOneOrNullResult()
  //        ;
  //    }
}
