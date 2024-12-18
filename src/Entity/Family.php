<?php

namespace App\Entity;

use App\Entity\Trait\TitleTrait;
use App\Validator as AcmeAssert;
use App\Repository\FamilyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FamilyRepository::class)]
#[AcmeAssert\FamilyParent]
class Family
{
    use TitleTrait;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private array $features = [];

    /**
     * @var Collection<int, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'familyName')]
    private Collection $products;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'families')]
    private ?self $parent = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent', fetch: 'EAGER')]
    private Collection $families;

    #[ORM\Column]
    private ?float $coef = null;

    /**
     * @var Collection<int, FamilyFeature>
     */
    #[ORM\OneToMany(targetEntity: FamilyFeature::class, mappedBy: 'family')]
    private Collection $familyFeatures;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->families = new ArrayCollection();
        $this->familyFeatures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    public function getFeatures(): array
    {
        return $this->features;
    }

    public function setFeatures(array $features): static
    {
        $this->features = $features;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setFamilyName($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getFamilyName() === $this) {
                $product->setFamilyName(null);
            }
        }

        return $this;
    }

    public function getParents(): ?self
    {
        return $this->parent;
    }

    public function setParents(?self $parents): static
    {
        $this->parent = $parents;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getFamilies(): Collection
    {
        return $this->families;
    }

    public function addFamily(self $family): static
    {
        if (!$this->families->contains($family)) {
            $this->families->add($family);
            $family->setParents($this);
        }

        return $this;
    }

    public function removeFamily(self $family): static
    {
        if ($this->families->removeElement($family)) {
            // set the owning side to null (unless already changed)
            if ($family->getParents() === $this) {
                $family->setParents(null);
            }
        }

        return $this;
    }

    public function getCoef(): ?float
    {
        return $this->coef;
    }

    public function setCoef(float $coef): static
    {
        $this->coef = $coef;

        return $this;
    }

    /**
     * @return Collection<int, FamilyFeature>
     */
    public function getFamilyFeatures(): Collection
    {
        return $this->familyFeatures;
    }

    public function addFamilyFeature(FamilyFeature $familyFeature): static
    {
        if (!$this->familyFeatures->contains($familyFeature)) {
            $this->familyFeatures->add($familyFeature);
            $familyFeature->setFamily($this);
        }

        return $this;
    }

    public function removeFamilyFeature(FamilyFeature $familyFeature): static
    {
        if ($this->familyFeatures->removeElement($familyFeature)) {
            if ($familyFeature->getFamily() === $this)
            $familyFeature->setFamily(null);
        }

        return $this;
    }

}
