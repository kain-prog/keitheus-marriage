<?php

namespace App\Bundles\GuestBundle\Entity;

use App\Bundles\GuestBundle\Repository\GuestRepository;
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

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $guestNotCome = null;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $companions_number = 0;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $message = null;

    #[ORM\Column(type: 'boolean', nullable: false)]
    private bool $response = false;

    #[ORM\ManyToMany(targetEntity: Companion::class, inversedBy: 'guests')]
    #[ORM\JoinTable(name: "guest_companion")]
    private Collection $companions;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $updated_at = null;

    public function __construct()
    {
        $this->companions = new ArrayCollection();
    }

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

    public function getGuestNotCome(): ?string
    {
        return $this->guestNotCome;
    }

    public function setGuestNotCome(?string $guestNotCome): void
    {
        $this->guestNotCome = $guestNotCome;
    }

    public function getCompanionsNumber(): int
    {
        return $this->companions_number;
    }

    public function setCompanionsNumber(int $companions_number): void
    {
        $this->companions_number = $companions_number;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function getResponse(): ?bool
    {
        return $this->response;
    }

    public function setResponse(?bool $response): void
    {
        $this->response = $response;
    }

    public function getCompanions(): Collection
    {
        return $this->companions;
    }

    public function addCompanion(Companion $companion): self
    {
        if (!$this->companions->contains($companion)) {
            $this->companions[] = $companion;
        }

        return $this;
    }

    public function removeCompanion(Companion $companion): self
    {
        $this->companions->removeElement($companion);
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
