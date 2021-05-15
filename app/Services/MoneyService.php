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
     * @return int[]
     */
    public function getMoneyReturnEmpty(): array
    {
        return [
            'bill_100000'   => 0,
            'bill_50000'    => 0,
            'bill_20000'    => 0,
            'bill_10000'    => 0,
            'bill_5000'     => 0,
            'bill_2000'     => 0,
            'bill_1000'     => 0,
            'coin_1000'     => 0,
            'coin_500'      => 0,
            'coin_200'      => 0,
            'coin_100'      => 0,
            'coin_50'       => 0,
        ];
    }

    /**
     * @param $movementDenominations
     * @return int
     */
    public static function getTotalCashReceived($movementDenominations): int
    {
        $totalMoney = 0;

        foreach ($movementDenominations as $movementAttribute => $amount){
            foreach (self::MONEY_DENOMINATION as $key => $money) {
                if($movementAttribute == $key && abs($amount) > 0){
                    $totalMoney += $money * $amount;
                    break;
                }
            }
        }

        return $totalMoney;
    }

    /**
     * @param int $money
     * @param array $boxTotal
     * @return int[]
     */
    public function getDenominationOfMoneyToReturn(int $money, array $boxTotal): array
    {
        $moneyReturn = $this->getMoneyReturnEmpty();
        foreach ($moneyReturn as $currencyDenomination => $amount) {
            foreach ($boxTotal as $key => $value) {
                if($money >= self::MONEY_DENOMINATION[$key]){
                    $remainder = (int) ($money / self::MONEY_DENOMINATION[$key]);

                    $moneyReturn[$key] += $remainder;

                    $money = $money % self::MONEY_DENOMINATION[$key];
                }
            }
        }

        return $moneyReturn;
    }
}
