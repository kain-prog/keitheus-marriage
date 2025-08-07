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

    public function getTotalPrice(): string
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('SUM(p.price) AS total')
            ->where('p.is_presented = :true')
            ->setParameter('true', true);

        $result = $queryBuilder->getQuery()->getSingleScalarResult();
        return $result !== null ? (string) $result : '0';
    }

    public function getProductsByCategories(?array $categories): array
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.categories', 'c')
            ->where('c.name IN (:names)')
            ->setParameter('names', $categories);

        return $qb->getQuery()->getResult();
    }

    public function save(Product $product): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($product);
        $entityManager->flush();
    }
}
