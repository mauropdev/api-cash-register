<?php

namespace App\Repositories;

use App\Models\Movement;

class MovementRepository
{
    /**
     * @var Movement
     */
    private Movement $movement;

    /**
     * MovementRepository constructor.
     * @param Movement $Movement
     */
    public function __construct(Movement $Movement)
    {
        $this->movement = $Movement;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->movement::create($data);
    }

    /**
     * @return mixed
     */
    public function getBoxTotal()
    {
        return $this->movement::selectRaw($this->getSelectTotalRaw())->first()->toArray();
    }

    /**
     * @return mixed
     */
    public function getStatusBox()
    {
        return $this->movement::selectRaw($this->getSelectTotalRaw())->first();
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getEventLogs(array $params = [])
    {
        $eventLogs = $this->movement::select('movements.*', 'movement_types.name as movement_type');

        if (isset($params['start_date']) && $params['start_date'] != ''){
            $eventLogs = $eventLogs->where('movements.created_at', '>=', "{$params['start_date']} 00:00:00");
        }

        if (isset($params['end_date']) && $params['end_date'] != ''){
            $eventLogs = $eventLogs->where('movements.created_at', '<=', "{$params['end_date']} 23:59:59");
        }

        return $eventLogs->join('movement_types', 'movements.movement_type_id', 'movement_types.id')->get();
    }

    /**
     * @return string
     */
    private function getSelectTotalRaw(): string
    {
        return '
            sum(bill_100000) as bill_100000,
            sum(bill_50000) as bill_50000,
            sum(bill_20000) as bill_20000,
            sum(bill_10000) as bill_10000,
            sum(bill_5000) as bill_5000,
            sum(bill_2000) as bill_2000,
            sum(bill_1000) as bill_1000,
            sum(coin_1000) as coin_1000,
            sum(coin_500) as coin_500,
            sum(coin_200) as coin_200,
            sum(coin_100) as coin_100,
            sum(coin_50) as coin_50
        ';
    }
}
