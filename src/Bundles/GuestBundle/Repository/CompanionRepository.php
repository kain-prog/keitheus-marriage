<?php

namespace App\Bundles\GuestBundle\Repository;

use App\Bundles\GuestBundle\Entity\Companion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Companion>
 */
class CompanionRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
    )
    {
        parent::__construct($registry, Companion::class);
    }

    public function save(Companion $companion): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($companion);
        $entityManager->flush();
    }
}
