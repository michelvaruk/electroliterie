<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait FullnameTrait
{
    #[ORM\Column(length: 100)]
    #[Assert\Length(
        min: 2,
        minMessage: 'Le nom de famille ne peut contenir moins de {{ limit }} caractères.',
        max: 100,
        maxMessage: 'Le nom de famille ne peut contenir plus de {{ limit }} caractères.',
    )]
    #[Assert\NotBlank(message: "Merci de renseigner ce champ.")]
    private ?string $lastname = null;

    #[ORM\Column(length: 150, nullable: true)]
    #[Assert\Length(
        min: 2,
        minMessage: 'Le prénom ne peut contenir moins de {{ limit }} caractères.',
        max: 150,
        maxMessage: 'Le prénom ne peut contenir plus de {{ limit }} caractères.',
    )]
    private ?string $firstname = null;

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }
}
