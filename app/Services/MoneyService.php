<?php

namespace App\Services;

class MoneyService
{
    const MONEY_DENOMINATION = [
        'bill_100000'   => 100000,
        'bill_50000'    => 50000,
        'bill_20000'    => 20000,
        'bill_10000'    => 10000,
        'bill_5000'     => 5000,
        'bill_2000'     => 2000,
        'bill_1000'     => 1000,
        'coin_1000'     => 1000,
        'coin_500'      => 500,
        'coin_200'      => 200,
        'coin_100'      => 100,
        'coin_50'       => 50,
    ];

    /**
     * @param $movementDenominations
     * @return int
     */
    public static function getTotalMoney($movementDenominations): int
    {
        $totalMoney = 0;

        foreach ($movementDenominations as $movementAttribute => $amount){
            foreach (self::MONEY_DENOMINATION as $key => $money) {
                if($movementAttribute == $key && $movementDenominations[$key] > 0){
                    $totalMoney += $money * $amount;
                    break;
                }
            }
        }

        return $totalMoney;
    }
}
