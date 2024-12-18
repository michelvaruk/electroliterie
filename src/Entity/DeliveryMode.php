<?php

namespace App\Entity;

use App\Entity\Trait\ActiveTrait;
use App\Entity\Trait\DescriptionTrait;
use App\Entity\Trait\TitleTrait;
use App\Repository\DeliveryModeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryModeRepository::class)]
class DeliveryMode
{
    use ActiveTrait;
    use TitleTrait;
    use DescriptionTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $price = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'deliveryMode')]
    private Collection $orders;

    #[ORM\Column(nullable: true)]
    private ?float $area = null;

    #[ORM\Column(nullable: true)]
    private ?int $offsidePrice = null;

    #[ORM\Column(nullable: true)]
    private ?float $offsideArea = null;

    #[ORM\Column(nullable: true)]
    private ?float $maxDistance = null;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price / 100;
    }

    public function setPrice(?float $price): static
    {
        $this->price = round($price * 100);

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setDeliveryMode($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getDeliveryMode() === $this) {
                $order->setDeliveryMode(null);
            }
        }

        return $this;
    }

    public function getArea(): ?float
    {
        return $this->area;
    }

    public function setArea(?float $area): static
    {
        $this->area = $area;

        return $this;
    }

    public function getOffsidePrice(): ?float
    {
        return $this->offsidePrice / 100;
    }

    public function setOffsidePrice(?float $offsidePrice): static
    {
        $this->offsidePrice = round($offsidePrice * 100);

        return $this;
    }

    public function getOffsideArea(): ?float
    {
        return $this->offsideArea;
    }

    public function setOffsideArea(?float $offsideArea): static
    {
        $this->offsideArea = $offsideArea;

        return $this;
    }

    public function getMaxDistance(): ?float
    {
        return $this->maxDistance;
    }

    public function setMaxDistance(?float $maxDistance): static
    {
        $this->maxDistance = $maxDistance;

        return $this;
    }
}
