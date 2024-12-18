<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    use CreatedAtTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ['persist'], fetch: 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adress $invoiceAdress = null;

    #[ORM\ManyToOne(inversedBy: 'orders', cascade: ['persist'], fetch: 'EAGER')]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: true)]
    private ?DeliveryMode $deliveryMode = null;

    /**
     * @var Collection<int, InvoiceLine>
     */
    #[ORM\OneToMany(targetEntity: InvoiceLine::class, mappedBy: 'relatedOrder', orphanRemoval: true, cascade:['persist', 'remove'])]
    private Collection $invoiceLines;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $orderStatus = null;

    #[ORM\Column(nullable: true)]
    private ?int $deliveryTaxFreePrice = null;

    public function __construct()
    {
        $this->invoiceLines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getInvoiceAdress(): ?Adress
    {
        return $this->invoiceAdress;
    }

    public function setInvoiceAdress(?Adress $invoiceAdress): static
    {
        $this->invoiceAdress = $invoiceAdress;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getDeliveryMode(): ?DeliveryMode
    {
        return $this->deliveryMode;
    }

    public function setDeliveryMode(?DeliveryMode $deliveryMode): static
    {
        $this->deliveryMode = $deliveryMode;

        return $this;
    }

    /**
     * @return Collection<int, InvoiceLine>
     */
    public function getInvoiceLines(): Collection
    {
        return $this->invoiceLines;
    }

    public function addInvoiceLine(InvoiceLine $invoiceLine): static
    {
        if (!$this->invoiceLines->contains($invoiceLine)) {
            $this->invoiceLines->add($invoiceLine);
            $invoiceLine->setRelatedOrder($this);
        }

        return $this;
    }

    public function removeInvoiceLine(InvoiceLine $invoiceLine): static
    {
        if ($this->invoiceLines->removeElement($invoiceLine)) {
            // set the owning side to null (unless already changed)
            if ($invoiceLine->getRelatedOrder() === $this) {
                $invoiceLine->setRelatedOrder(null);
            }
        }

        return $this;
    }

    public function getOrderStatus(): ?string
    {
        return $this->orderStatus;
    }

    public function setOrderStatus(?string $orderStatus): static
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    public function getDeliveryTaxFreePrice(): ?float
    {
        return $this->deliveryTaxFreePrice / 100;
    }

    public function setDeliveryTaxFreePrice(?float $deliveryTaxFreePrice): static
    {
        $this->deliveryTaxFreePrice = round($deliveryTaxFreePrice * 100);

        return $this;
    }
}
