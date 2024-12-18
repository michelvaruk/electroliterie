<?php

namespace App\Service;

use App\Entity\Order;

class OrderService
{
    public function getTotal(?Order $order): array {
        (float) $totalHt = 0;
        (float) $totalVat = 0;
        (float) $totalTtc = 0;
        (float) $totalDeee = 0;

        foreach($order->getInvoiceLines() as $line) {
            $totalHt += $line->getTaxFreeUnitPrice() * $line->getQuantity();
            $totalVat += $line->getTaxFreeUnitPrice() * $line->getQuantity() * $line->getVatRate() / 100;
            $totalTtc += $line->getTaxFreeUnitPrice() * $line->getQuantity() * (100 + $line->getVatRate()) / 100;
            $totalDeee += $line->getTaxFreeDeee() * $line->getQuantity() * 1.2;
        }
        if($order->getDeliveryMode()) {
            $totalHt += $order->getDeliveryTaxFreePrice();
            $totalVat += $order->getDeliveryTaxFreePrice() * 0.2;
            $totalTtc += $order->getDeliveryTaxFreePrice() * 1.2;
        }
        return [
            'totalHt' => $totalHt,
            'totalVat' => $totalVat,
            'totalTtc' => $totalTtc,
            'totalDeee' => $totalDeee,
        ];
    }
}