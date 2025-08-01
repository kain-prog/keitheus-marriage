<?php

namespace App\Bundles\GuestBundle\Entity;

use App\Bundles\AddressBundle\Entity\Address;
use App\Bundles\CardBundle\Entity\Card;
use App\Bundles\GuestBundle\Repository\GuestRepository;
use App\Bundles\OrderBundle\Entity\Order;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[Groups(['guest'])]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Entity(repositoryClass: GuestRepository::class)]
#[ORM\Table(name: "guest", indexes: [])]
class Guest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private bool $is_confirmed = false;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $companions_number = 0;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getIsConfirmed(): bool
    {
        return $this->is_confirmed;
    }

    public function setIsConfirmed(bool $is_confirmed): void
    {
        $this->is_confirmed = $is_confirmed;
    }

    public function getCompanionsNumber(): int
    {
        return $this->companions_number;
    }

    public function setCompanionsNumber(int $companions_number): void
    {
        $this->companions_number = $companions_number;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->created_at = new \DateTime("now");
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updated_at = new \DateTime("now");
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
