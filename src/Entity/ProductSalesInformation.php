<?php

namespace App\Entity;

use App\Entity\Trait\ActiveTrait;
use App\Repository\ProductSalesInformationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductSalesInformationRepository::class)]
class ProductSalesInformation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $sellingPrice = null;

    #[ORM\Column]
    private ?int $purchasePrice = null;

    #[ORM\Column(nullable: true)]
    private ?int $ecoContribution = null;

    #[ORM\Column(nullable: true)]
    private ?int $discountPrice = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\OneToOne(inversedBy: 'salesInfo', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSellingPrice(): ?float
    {
        return $this->sellingPrice / 100;
    }

    public function setSellingPrice(float $sellingPrice): static
    {
        $this->sellingPrice = round($sellingPrice * 100);

        return $this;
    }

    public function getPurchasePrice(): ?float
    {
        return $this->purchasePrice / 100;
    }

    public function setPurchasePrice(float $purchasePrice): static
    {
        $this->purchasePrice = round($purchasePrice * 100);

        return $this;
    }

    public function getEcoContribution(): ?float
    {
        return $this->ecoContribution / 100;
    }

    public function setEcoContribution(?float $ecoContribution): static
    {
        $this->ecoContribution = round($ecoContribution * 100);

        return $this;
    }

    public function getdiscountPrice(): ?float
    {
        return $this->discountPrice / 100;
    }

    public function setdiscountPrice(?float $discountPrice): static
    {
        $this->discountPrice = round($discountPrice * 100);

        return $this;
    }


    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): static
    {
        $this->product = $product;

        return $this;
    }
}
