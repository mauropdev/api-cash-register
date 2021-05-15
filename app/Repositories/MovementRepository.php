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
}
