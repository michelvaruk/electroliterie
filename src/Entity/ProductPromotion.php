<?php

namespace App\Entity;

use App\Repository\ProductPromotionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductPromotionRepository::class)]
class ProductPromotion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'productPromotions', fetch: 'EAGER')]
    #[ORM\JoinColumn(onDelete: 'cascade')]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'productPromotions', cascade: ['persist', 'remove'], fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Promotion $promotion = null;

    #[ORM\Column(nullable: true)]
    private ?int $value = null;

    #[ORM\Column(nullable: true)]
    private ?bool $relative = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): static
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(?int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function isRelative(): ?bool
    {
        return $this->relative;
    }

    public function setRelative(?bool $relative): static
    {
        $this->relative = $relative;

        return $this;
    }
}
