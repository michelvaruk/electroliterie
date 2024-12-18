<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class FamilyParent extends Constraint
{
    public string $familyAlreadySubfamilyMessage = 'La famille choisie comme parent est déjà une sous-famille.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}