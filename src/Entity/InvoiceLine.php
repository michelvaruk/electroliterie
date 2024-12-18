<?php

namespace App\Entity;

use App\Repository\InvoiceLineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceLineRepository::class)]
class InvoiceLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'invoiceLines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $relatedOrder = null;

    #[ORM\ManyToOne(inversedBy: 'invoiceLines')]
    private ?Product $product = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?int $taxFreeUnitPrice = null;

    #[ORM\Column]
    private ?float $vatRate = null;

    #[ORM\Column(nullable: true)]
    private ?int $taxFreeDeee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelatedOrder(): ?Order
    {
        return $this->relatedOrder;
    }

    public function setRelatedOrder(?Order $relatedOrder): static
    {
        $this->relatedOrder = $relatedOrder;

        return $this;
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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTaxFreeUnitPrice(): ?float
    {
        return $this->taxFreeUnitPrice / 100;
    }

    public function setTaxFreeUnitPrice(float $taxFreeUnitPrice): static
    {
        $this->taxFreeUnitPrice = round($taxFreeUnitPrice * 100);

        return $this;
    }

    public function getVatRate(): ?float
    {
        return $this->vatRate;
    }

    public function setVatRate(float $vatRate): static
    {
        $this->vatRate = $vatRate;

        return $this;
    }

    public function getTaxFreeDeee(): ?float
    {
        return $this->taxFreeDeee / 100;
    }

    public function setTaxFreeDeee(?float $taxFreeDeee): static
    {
        $this->taxFreeDeee = round($taxFreeDeee * 100);

        return $this;
    }
}
