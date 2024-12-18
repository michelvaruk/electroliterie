<?php

namespace App\Entity;

use App\Entity\Trait\DescriptionTrait;
use App\Entity\Trait\PictureTrait;
use App\Entity\Trait\SlugTrait;
use App\Entity\Trait\TitleTrait;
use App\Entity\Trait\UpdatedAtTrait;
use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: PromotionRepository::class)]
#[Vich\Uploadable]
class Promotion
{
    use TitleTrait;
    use PictureTrait;
    use DescriptionTrait;
    use UpdatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $value = null;

    #[ORM\Column]
    private ?bool $relative = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $endDate = null;

    /**
     * @var Collection<int, ProductPromotion>
     */
    #[ORM\OneToMany(targetEntity: ProductPromotion::class, mappedBy: 'promotion', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $productPromotions;

    #[ORM\Column(nullable: true)]
    private ?bool $odr = null;

    public function __construct()
    {
        $this->productPromotions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function isRelative(): ?bool
    {
        return $this->relative;
    }

    public function setRelative(bool $relative): static
    {
        $this->relative = $relative;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

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
            $productPromotion->setPromotion($this);
        }

        return $this;
    }

    public function removeProductPromotion(ProductPromotion $productPromotion): static
    {
        if ($this->productPromotions->removeElement($productPromotion)) {
            // set the owning side to null (unless already changed)
            if ($productPromotion->getPromotion() === $this) {
                $productPromotion->setPromotion(null);
            }
        }

        return $this;
    }

    public function isOdr(): ?bool
    {
        return $this->odr;
    }

    public function setOdr(?bool $odr): static
    {
        $this->odr = $odr;

        return $this;
    }
}
