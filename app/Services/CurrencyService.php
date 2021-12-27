<?php

namespace App\Services;

class CurrencyService
{
    const RATES = [
        'cny' => [
            'usd' => 0.16
        ]
    ];

    public function convert($amount, $from, $to)
    {
        $rate = 0;
        if(isset(self::RATES[$from])){
            $rate = self::RATES[$from][$to] ?? 0;
        }

        return round($amount * $rate, 2);
    }
}
