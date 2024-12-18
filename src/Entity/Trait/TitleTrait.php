<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Serializer\Attribute\Groups;

trait TitleTrait
{
    #[ORM\Column(length: 255)]
    #[Groups('product:read')]
    private ?string $title = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups('product:read')]
    private $slug;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        $this->setSlug(strtolower($title));

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $title): self
    {
        $slugger = new AsciiSlugger();
        $this->slug = $slugger->slug($title);

        return $this;
    }
}
