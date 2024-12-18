<?php

namespace App\Service;

class DistanceService
{
    /**
     * Retourne la distance en metre ou kilometre (si $unit = 'k') entre deux latitude et longitude fournit
     */
    public static function distance($latitude, $longitude, $latMust = 43.496298, $lngMust = -1.496331, $unit = 'k') {
        $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
        $rlo1 = deg2rad($lngMust);
        $rla1 = deg2rad($latMust);
        $rlo2 = deg2rad($longitude);
        $rla2 = deg2rad($latitude);
        $dlo = ($rlo2 - $rlo1) / 2;
        $dla = ($rla2 - $rla1) / 2;
        $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
        $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
        //
        $meter = ($earth_radius * $d);
        if ($unit == 'k') {
            return $meter / 1000;
        }
        return $meter;
    }

    /**
     * Retourne le tableau des prix de livraison en fonction de l'éventuelle distance
     */
    public static function computePrice($deliveryMode, float $distance = 1000): float|null {
        if(!$deliveryMode->getArea() || $distance <= $deliveryMode->getArea() || $deliveryMode->getArea() == 0) {
            return $deliveryMode->getPrice();
        } elseif ($distance <= $deliveryMode->getMaxDistance()) {
            $x = intval(($distance - $deliveryMode->getArea()) / $deliveryMode->getOffsideArea());
            return $deliveryMode->getPrice() + $x * $deliveryMode->getOffsidePrice();
        } else {
            return null;
        }
    }
}