<?php

namespace App\Service;

use DateTime;

class ActivePromotions
{
    public static function isActive($promotion): bool {
        $today = new DateTime();
        if($promotion->getStartDate() < $today && $promotion->getEndDate() > $today) {
            $a = true;
        } else {
            $a = false;
        }
        return $a;
    }
}