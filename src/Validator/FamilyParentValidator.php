<?php

namespace App\Validator;

use App\Entity\Family;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class FamilyParentValidator extends ConstraintValidator
{
    /**
     * @param Family $family
     */
    public function validate($family, Constraint $constraint): void
    {
        if (!$family instanceof Family) {
            throw new UnexpectedValueException($family, Family::class);
        }

        if (!$constraint instanceof FamilyParent) {
            throw new UnexpectedValueException($constraint, FamilyParent::class);
        }

        if($family->getParent()) {
            if ($family->getParent()->getParent()) {
                $this->context
                    ->buildViolation($constraint->familyAlreadySubfamilyMessage)
                    ->atPath('family.parent')
                    ->addViolation();
            }
        }
    }
}