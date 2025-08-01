<?php

namespace App\Bundles\CategoryBundle\Repository;

use App\Bundles\CategoryBundle\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
    )
    {
        parent::__construct($registry, Category::class);
    }
}
