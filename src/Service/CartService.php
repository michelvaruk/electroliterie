<?php

namespace App\Service;

class CartService
{
    public function __construct(
    ) {
    }

    public function add($storedCart,string $id): array
    {
        if (array_key_exists($id, $storedCart)) {
            $storedCart[$id] ++;

        } else {
            $storedCart[$id] = 1;
        }
        return $storedCart;
    }

    public function remove($storedCart,string $id): array
    {
        if (array_key_exists($id, $storedCart)) {
            if($storedCart[$id] > 1) {
                $storedCart[$id] --;
            } else {
                unset($storedCart[$id]);
            }
        }
        return $storedCart;
    }

    public function deleteProduct($storedCart,string $id): array
    {
        $newCart = [];
        foreach($storedCart as $cartId => $qty) {
            if($id != $cartId) {
                $newCart[strval($cartId)] = $qty;
            }
        }
        return $newCart;
    }
}