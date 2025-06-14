<?php

namespace App\Services;

class ShippingCalculatorService
{
    public function calculate(array $data): array
    {
        $zone = $data['destination_type'];

        $tiers = [
            'normal' => [14, 12, 11],
            'remote' => [49, 47, 46],
        ];

        $shipments = $data['monthly_shipments'];
        $tier = $shipments <= 250 ? 0 : ($shipments <= 500 ? 1 : 2); // TODO Database or enum File
        $base = $tiers[$zone][$tier];

        $volumetric = ($data['dimensions']['length'] * $data['dimensions']['width'] * $data['dimensions']['height']) / 5000; // TODO Enum
        $usedWeight = max($volumetric, $data['weight']);
        $extraKg = max(0, $usedWeight - 5);
        $extraFee = $extraKg * 2;

        $subtotal = $base + $extraFee;
        $fuel = round($subtotal * 0.02, 2);
        $subtotal += $fuel;
        $subtotal += 5.25; // packaging

        $epg = max(2, round($subtotal * 0.10, 2));
        $subtotal += $epg;

        $vat = round($subtotal * 0.05, 2);
        $total = round($subtotal + $vat, 2);

        return [
            'zone' => $zone,
            'base_fee' => $base,
            'volumetric_weight' => round($volumetric, 2),
            'used_weight' => round($usedWeight, 2),
            'extra_weight_fee' => round($extraFee, 2),
            'fuel_surcharge' => $fuel,
            'packaging_fee' => 5.25,
            'epg_levy' => $epg,
            'vat' => $vat,
            'total' => $total,
        ];
    }
}
