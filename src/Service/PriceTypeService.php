<?php

namespace App\Service;

use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\Collection;

class PriceTypeService
{
    private $promotion;
    public function __construct(PromotionRepository $promotion)
    {
        $this->promotion = $promotion;
    }
    public static function getRealPrice($product): ?string {
        $product->getSalesInfo()->getdiscountPrice() ?
            $price = $product->getSalesInfo()->getdiscountPrice() :
            $price = $product->getSalesInfo()->getSellingPrice();
        if ($price < ($product->getSalesInfo()->getPurchasePrice() * $product->getFamilyName()->getCoef()))
            $price = $product->getSalesInfo()->getPurchasePrice() * $product->getFamilyName()->getCoef();
        return $product->getTitle() . ' - ' . $price . ' €';
    }
    public function getAllCurrentPrices($products): array {
        foreach($products as $product) {
            $this->getCurrentPrice($product);
        }
        return $products;
    }

    public function getCurrentPrice($product) {
        $promoPrice = 0;
        $product->getCurrentPrice() > 0 ?
                $price = $product->getCurrentPrice() :
                $price = $this->whichPrice($product);
            foreach($product->getProductPromotions() as $productPromotion) {
                $promotion = $productPromotion->getPromotion();
                if(ActivePromotions::isActive($promotion)) {
                    if(!$promotion->isOdr()) {
                        if($promotion->getValue() > 0) {
                            if($promotion->isRelative()) {
                                $promoPrice = ($price * $promotion->getValue() / 100);
                            } else {
                                $promoPrice = ($price - $promotion->getValue());
                            }
                        }
                        if($productPromotion->getValue() > 0) {
                            if($productPromotion->isRelative()) {
                                $promoPrice = ($price * $productPromotion->getValue() / 100);
                            } else {
                                $promoPrice = ($price - $productPromotion->getValue());
                            }
                        }
                    }
                }
            }
            $minPrice = $product->getSalesInfo()->getPurchasePrice() * $product->getFamilyName()->getCoef();
            if($promoPrice != 0) {
                $promoPrice < $minPrice ?
                    $product->setCurrentPrice($minPrice) :
                    $product->setCurrentPrice($promoPrice);
            }
            if(!$product->getCurrentPrice())
                $product->setCurrentPrice($price);
            return $product;
    }

    private function whichPrice($product): float {
        $product->getSalesInfo()->getdiscountPrice() ?
            $price = $product->getSalesInfo()->getdiscountPrice() :
            $price = $product->getSalesInfo()->getSellingPrice();
        return $price;
    }
}