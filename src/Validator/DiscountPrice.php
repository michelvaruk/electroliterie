<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class DiscountPrice extends Constraint
{
    public string $discountPriceMessage = 'Attention: le prix promo est inférieur au prix plancher déterminé par le prix d\'achat du produit et le coefficient minimal de la famille. Baisser une de ces deux valeurs pour pouvoir forcer ce prix bas.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}