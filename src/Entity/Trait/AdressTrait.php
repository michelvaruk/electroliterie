<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


trait AdressTrait
{

    #[ORM\Column(length: 255)]
    private ?string $adress = null;

    #[ORM\Column(length: 5)]
    #[Assert\Regex('/^[1-9]{5}+/')]
    private ?string $zipCode = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }
}
