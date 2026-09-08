<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  #[Groups(['order:read'])]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  #[Groups(['order:read'])]
  private ?string $customerName = null;

  #[ORM\Column(length: 255)]
  #[Groups(['order:read'])]
  private ?string $customerEmail = null;

  #[ORM\Column(length: 255)]
  #[Groups(['order:read'])]
  private ?string $status = null;

  #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
  #[Groups(['order:read'])]
  private ?string $total = null;

  #[ORM\Column]
  #[Groups(['order:read'])]
  private ?\DateTimeImmutable $createdAt = null;

  /**
   * @var Collection<int, OrderItem>
   */
  #[ORM\OneToMany(targetEntity: OrderItem::class, mappedBy: 'parentOrder', orphanRemoval: true)]
  #[Groups(['order:read'])]
  private Collection $items;

  public function __construct()
  {
    $this->items = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getCustomerName(): ?string
  {
    return $this->customerName;
  }

  public function setCustomerName(string $customerName): static
  {
    $this->customerName = $customerName;

    return $this;
  }

  public function getCustomerEmail(): ?string
  {
    return $this->customerEmail;
  }

  public function setCustomerEmail(string $customerEmail): static
  {
    $this->customerEmail = $customerEmail;

    return $this;
  }

  public function getStatus(): ?string
  {
    return $this->status;
  }

  public function setStatus(string $status): static
  {
    $this->status = $status;

    return $this;
  }

  public function getTotal(): ?string
  {
    return $this->total;
  }

  public function setTotal(string $total): static
  {
    $this->total = $total;

    return $this;
  }

  public function getCreatedAt(): ?\DateTimeImmutable
  {
    return $this->createdAt;
  }

  public function setCreatedAt(\DateTimeImmutable $createdAt): static
  {
    $this->createdAt = $createdAt;

    return $this;
  }

  /**
   * @return Collection<int, OrderItem>
   */
  public function getItems(): Collection
  {
    return $this->items;
  }

  public function addItem(OrderItem $item): static
  {
    if (!$this->items->contains($item)) {
      $this->items->add($item);
      $item->setParentOrder($this);
    }

    return $this;
  }

  public function removeItem(OrderItem $item): static
  {
    if ($this->items->removeElement($item)) {
      // set the owning side to null (unless already changed)
      if ($item->getParentOrder() === $this) {
        $item->setParentOrder(null);
      }
    }

    return $this;
  }
}
