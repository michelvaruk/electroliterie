<?php

namespace App\Service;

class FilterService {
    public static function type($products): array
    {
        $types = [];
        foreach($products as $product) {
            $type = $product->getFeature()->getType();
            if(!in_array($type, $types))
                $types[] = $type;
        }
        return ['type' => $types];
    }
    public static function support($products): array
    {
        $supports = [];
        foreach($products as $product) {
            $support = $product->getFeature()->getSupport();
            if(!in_array($support, $supports))
                $supports[] = $support;
        }
        return ['support' => $supports];
    }

    public static function cover($products): array
    {
        $covers = [];
        foreach($products as $product) {
            $cover = $product->getFeature()->getCover();
            if(!in_array($cover, $covers))
                $covers[] = $cover;
        }
        return ['cover' => $covers];
    }

    public static function technology($products): array
    {
        $technologies = [];
        foreach($products as $product) {
            $technology = $product->getFeature()->getTechnology();
            if(!in_array($technology, $technologies))
                $technologies[] = $technology;
        }
        return ['technology' => $technologies];
    }

    public static function colorName($products): array
    {
        $colorNames = [];
        foreach($products as $product) {
            $colorName = $product->getFeature()->getColorName();
            if(!in_array($colorName, $colorNames))
                $colorNames[] = $colorName;
        }
        return ['colorName' => $colorNames];
    }

    public static function feetShape($products): array
    {
        $feetShapes = [];
        foreach($products as $product) {
            $feetShape = $product->getFeature()->getFeetShape();
            if(!in_array($feetShape, $feetShapes))
                $feetShapes[] = $feetShape;
        }
        return ['feetShape' => $feetShapes];
    }

    public static function brand($products): array
    {
        $brands = [];
        foreach($products as $product) {
            $brand = $product->getBrand()->getTitle();
            if(!in_array($brand, $brands))
                $brands[] = $brand;
        }
        return ['brand' => $brands];
    }


    public static function length($products): array
    {
        $minLength = null;
        $maxLength = null;
        foreach ($products as $product) {
            $length = $product->getFeature()->getLength();
            if (!$minLength)
                $minLength = $length;
            if ($length < $minLength)
                $minLength = $length;
            if (!$maxLength)
                $maxLength = $length;
            if ($length > $maxLength)
                $maxLength = $length;
        }
        return [
            'length' => [
                'min' => $minLength,
                'max' => $maxLength
                ]
        ];
    }

    public static function width($products): array
    {
        $minWidth = null;
        $maxWidth = null;
        foreach ($products as $product) {
            $width = $product->getFeature()->getWidth();
            if (!$minWidth)
                $minWidth = $width;
            if ($width < $minWidth)
                $minWidth = $width;
            if (!$maxWidth)
                $maxWidth = $width;
            if ($width > $maxWidth)
                $maxWidth = $width;
        }
        return [
            'width' => [
                'min' => $minWidth,
                'max' => $maxWidth
                ]
        ];
    }

    public static function height($products): array
    {
        $minHeight = null;
        $maxHeight = null;
        foreach ($products as $product) {
            $height = $product->getFeature()->getHeight();
            if (!$minHeight)
                $minHeight = $height;
            if ($height < $minHeight)
                $minHeight = $height;
            if (!$maxHeight)
                $maxHeight = $height;
            if ($height > $maxHeight)
                $maxHeight = $height;
        }
        return [
            'height' => [
                'min' => $minHeight,
                'max' => $maxHeight
                ]
        ];
    }

    public static function price($products): array
    {
        $minPrice = null;
        $maxPrice = null;
        foreach ($products as $product) {
            $price = $product->getCurrentPrice();
            if (!$minPrice)
                $minPrice = $price;
            if ($price < $minPrice)
                $minPrice = $price;
            if (!$maxPrice)
                $maxPrice = $price;
            if ($price > $maxPrice)
                $maxPrice = $price;
        }
        return [
            'price' => [
                'min' => $minPrice,
                'max' => $maxPrice
                ]
        ];
    }
}