<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


trait UrlTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Url(
        message: 'L\'url {{ value }} n\'est pas une url valide.',
    )]
    private ?string $url = null;

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }
}
