<?php

namespace App\Bundles\ProductBundle\Repository;

use App\Bundles\ProductBundle\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry   $registry,
    )
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $product): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($product);
        $entityManager->flush();
    }
}
