<?php

namespace App\Entity;

use App\Entity\Trait\ActiveTrait;
use App\Entity\Trait\DescriptionTrait;
use App\Entity\Trait\PdfTrait;
use App\Entity\Trait\PictureTrait;
use App\Entity\Trait\TitleTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Validator as AcmeAssert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[Vich\Uploadable]
#[AcmeAssert\DiscountPrice()]
class Product
{
    use ActiveTrait;
    use TitleTrait;
    use DescriptionTrait;
    use PictureTrait;
    use PdfTrait;
    use UpdatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('product:read')]
    private ?int $id = null;

    #[ORM\Column(length: 13)]
    #[Groups('product:read')]
    private ?string $EAN = null;

    #[ORM\Column(length: 255)]
    #[Groups('product:read')]
    private ?string $reference = null;

    /**
     * @var Collection<int, ProductPictures>
     */
    #[ORM\OneToMany(targetEntity: ProductPictures::class, mappedBy: 'product', orphanRemoval: true, cascade: ['persist', 'remove'], fetch: 'EAGER')]
    #[Groups('product:read')]
    private Collection $productPictures;

    #[ORM\ManyToOne(inversedBy: 'products', fetch: 'EAGER')]
    #[Groups('product:read')]
    private ?Brand $brand = null;

    #[ORM\OneToOne(mappedBy: 'product', cascade: ['persist', 'remove'])]
    private ?ProductSalesInformation $salesInfo = null;

    #[Groups('product:read')]
    private ?float $currentPrice = null;

    /**
     * @var Collection<int, ProductPromotion>
     */
    #[ORM\OneToMany(targetEntity: ProductPromotion::class, mappedBy: 'product', fetch: 'EAGER')]
    private Collection $productPromotions;

    #[ORM\Column(nullable: true)]
    private ?bool $selection = null;

    #[ORM\ManyToOne(inversedBy: 'products', fetch:'EAGER')]
    private ?Family $familyName = null;

    /**
     * @var Collection<int, InvoiceLine>
     */
    #[ORM\OneToMany(targetEntity: InvoiceLine::class, mappedBy: 'product')]
    private Collection $invoiceLines;

    /**
     * @var Collection<int, ProductFeature>
     */
    #[ORM\OneToMany(targetEntity: ProductFeature::class, mappedBy: 'product')]
    private Collection $productFeatures;

    public function __construct()
    {
        $this->productPictures = new ArrayCollection();
        $this->productPromotions = new ArrayCollection();
        $this->invoiceLines = new ArrayCollection();
        $this->productFeatures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEAN(): ?string
    {
        return $this->EAN;
    }

    public function setEAN(string $EAN): static
    {
        $this->EAN = $EAN;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return Collection<int, ProductPictures>
     */
    public function getProductPictures(): Collection
    {
        return $this->productPictures;
    }

    public function addProductPicture(ProductPictures $productPicture): static
    {
        if (!$this->productPictures->contains($productPicture)) {
            $this->productPictures->add($productPicture);
            $productPicture->setProduct($this);
        }

        return $this;
    }

    public function removeProductPicture(ProductPictures $productPicture): static
    {
        if ($this->productPictures->removeElement($productPicture)) {
            // set the owning side to null (unless already changed)
            if ($productPicture->getProduct() === $this) {
                $productPicture->setProduct(null);
            }
        }

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getSalesInfo(): ?ProductSalesInformation
    {
        return $this->salesInfo;
    }

    public function setSalesInfo(ProductSalesInformation $salesInfo): static
    {
        // set the owning side of the relation if necessary
        if ($salesInfo->getProduct() !== $this) {
            $salesInfo->setProduct($this);
        }

        $this->salesInfo = $salesInfo;

        return $this;
    }

    public function getCurrentPrice(): ?string
    {
        return $this->currentPrice;
    }

    public function setCurrentPrice(?string $currentPrice): static
    {
        $this->currentPrice = $currentPrice;
        return $this;
    }

    /**
     * @return Collection<int, ProductPromotion>
     */
    public function getProductPromotions(): Collection
    {
        return $this->productPromotions;
    }

    public function addProductPromotion(ProductPromotion $productPromotion): static
    {
        if (!$this->productPromotions->contains($productPromotion)) {
            $this->productPromotions->add($productPromotion);
            $productPromotion->setProduct($this);
        }

        return $this;
    }

    public function removeProductPromotion(ProductPromotion $productPromotion): static
    {
        if ($this->productPromotions->removeElement($productPromotion)) {
            // set the owning side to null (unless already changed)
            if ($productPromotion->getProduct() === $this) {
                $productPromotion->setProduct(null);
            }
        }

        return $this;
    }

    public function isSelection(): ?bool
    {
        return $this->selection;
    }

    public function setSelection(?bool $selection): static
    {
        $this->selection = $selection;

        return $this;
    }

    public function getFamilyName(): ?Family
    {
        return $this->familyName;
    }

    public function setFamilyName(?Family $familyName): static
    {
        $this->familyName = $familyName;

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
            $invoiceLine->setProduct($this);
        }

        return $this;
    }

    public function removeInvoiceLine(InvoiceLine $invoiceLine): static
    {
        if ($this->invoiceLines->removeElement($invoiceLine)) {
            // set the owning side to null (unless already changed)
            if ($invoiceLine->getProduct() === $this) {
                $invoiceLine->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ProductFeature>
     */
    public function getProductFeatures(): Collection
    {
        return $this->productFeatures;
    }

    public function addProductFeature(ProductFeature $productFeature): static
    {
        if (!$this->productFeatures->contains($productFeature)) {
            $this->productFeatures->add($productFeature);
            $productFeature->setProduct($this);
        }

        return $this;
    }

    public function removeProductFeature(ProductFeature $productFeature): static
    {
        if ($this->productFeatures->removeElement($productFeature)) {
            // set the owning side to null (unless already changed)
            if ($productFeature->getProduct() === $this) {
                $productFeature->setProduct(null);
            }
        }

        return $this;
    }
}
