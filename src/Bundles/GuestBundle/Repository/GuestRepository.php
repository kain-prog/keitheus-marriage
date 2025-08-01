<?php

namespace App\Bundles\GuestBundle\Repository;

use App\Bundles\GuestBundle\Entity\Guest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Guest>
 */
class GuestRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
    )
    {
        parent::__construct($registry, Guest::class);
    }

    public function save(Guest $guest): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($guest);
        $entityManager->flush();
    }
}
