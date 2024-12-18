<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait PhoneTrait
{
    #[ORM\Column(length: 15, nullable: true)]
    // #[Assert\Regex('/^(0|\\+33|0033)[1-9][0-9]{8}+/', 'Le numéro de téléphone renseigné n\'est pas valide.')]
    private ?string $phone = null;

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }
}
