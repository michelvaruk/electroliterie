<?php

namespace App\Validator;

use App\Entity\Product;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class DiscountPriceValidator extends ConstraintValidator
{
    /**
     * @param Product $product
     */
    public function validate($product, Constraint $constraint): void
    {
        if (!$product instanceof Product) {
            throw new UnexpectedValueException($product, Product::class);
        }

        if (!$constraint instanceof DiscountPrice) {
            throw new UnexpectedValueException($constraint, DiscountPrice::class);
        }

        if($product->getSalesInfo() && $product->getSalesInfo()->getdiscountPrice() && $product->getSalesInfo()->getdiscountPrice() < $product->getSalesInfo()->getPurchasePrice() * $product->getFamilyName()->getCoef()) {
            $this->context
                ->buildViolation($constraint->discountPriceMessage)
                ->atPath('product.salesInfo.discountPrice')
                ->addViolation();
        }
    }
}