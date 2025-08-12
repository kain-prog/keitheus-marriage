<?php

namespace App\Bundles\GuestBundle\Entity;

use App\Bundles\GuestBundle\Repository\CompanionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[Groups(['companion'])]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Entity(repositoryClass: CompanionRepository::class)]
#[ORM\Table(name: "companion", indexes: [])]
class Companion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $name;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private bool $is_child = false;

    #[ORM\ManyToMany(targetEntity: Guest::class, mappedBy: 'companions')]
    private Collection $guests;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $updated_at = null;

    public function __construct()
    {
        $this->guests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isChild(): bool
    {
        return $this->is_child;
    }

    public function setIsChild(bool $is_child): void
    {
        $this->is_child = $is_child;
    }

    public function addGuest(Guest $guest): self
    {
        if (!$this->guests->contains($guest)) {
            $this->guests[] = $guest;
            $guest->addCompanion($this);
        }

        return $this;
    }

    public function removeGuest(Guest $guest): self
    {
        if ($this->guests->removeElement($guest)) {
            $guest->removeCompanion($this);
        }

        return $this;
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
