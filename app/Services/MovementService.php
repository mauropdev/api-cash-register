<?php

namespace App\Services;

use App\Repositories\MovementRepository;

class MovementService
{
    /**
     * @var MovementRepository
     */
    private MovementRepository $movementRepository;

    /**
     * MovementService constructor.
     * @param MovementRepository $movementRepository
     */
    public function __construct(MovementRepository $movementRepository)
    {
        $this->movementRepository = $movementRepository;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function loadBaseToBox(array $data)
    {
        $movement = $this->prepareMovement(MovementTypeService::LOAD_BOX, $data);
        return $this->movementRepository->create($movement);
    }

    /**
     * @param int $movement_type_id
     * @param array $data
     * @return array
     */
    private function prepareMovement(int $movement_type_id, array $data): array
    {
        $data['movement_type_id'] = $movement_type_id;
        return $data;
    }
}
