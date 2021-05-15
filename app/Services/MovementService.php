<?php

namespace App\Services;

use App\Exceptions\BusinessLogicException;
use App\Repositories\MovementRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class MovementService
{
    /**
     * @var MovementRepository
     */
    private MovementRepository $movementRepository;

    /**
     * @var MoneyService
     */
    private MoneyService $moneyService;

    /**
     * MovementService constructor.
     * @param MoneyService $moneyService
     * @param MovementRepository $movementRepository
     */
    public function __construct(MoneyService $moneyService, MovementRepository $movementRepository)
    {
        $this->movementRepository = $movementRepository;
        $this->moneyService = $moneyService;
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
     * @return mixed
     * @throws BusinessLogicException
     */
    public function unloadBaseToBox()
    {
        $boxTotal   = $this->movementRepository->getBoxTotal();

        if($boxTotal['total_money'] <= 0){
            throw new BusinessLogicException('no money in the box.');
        }

        $boxTotal = $this->setAsNegativeValues($boxTotal);
        $movement = $this->prepareMovement(MovementTypeService::UNLOAD_BOX, $boxTotal);

        return $this->movementRepository->create($movement);
    }
    /**
     * @param array $data
     * @return mixed
     * @throws BusinessLogicException
     */
    public function makePayment(array $data)
    {
        $returnCash     = false;
        $moneyToReturn  = $this->moneyService->getMoneyReturnEmpty();
        $totalMoney     = $this->moneyService::getTotalCashReceived($data);
        $boxTotal       = $this->movementRepository->getBoxTotal();

        if( !$this->validateAmountAndMoney($data['amount'], $totalMoney)){
            throw new BadRequestException('the amount to pay is greater than the cash received.');
        }

        if($this->mustReturnMoney($data['amount'], $totalMoney)){
            $returnCash = true;
            $moneyToReturn = $this->getReturnMoney($totalMoney - $data['amount'], $boxTotal);
        }

        $movement = $this->prepareMovement(MovementTypeService::PAYMENT_MADE, $data);
        $pay = $this->movementRepository->create($movement);

        if($returnCash){
            $moneyToReturn  = $this->setAsNegativeValues($moneyToReturn);
            $movement       = $this->prepareMovement(MovementTypeService::RETURN_OF_PAYMENT_MADE, $moneyToReturn);

            return $this->movementRepository->create($movement);
        }

        return $moneyToReturn;
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

    /**
     * @param array $data
     * @return int[]
     */
    private function setAsNegativeValues(array $data): array
    {
        return array_map(function($item){
            return $item * -1;
        }, $data);
    }

    /**
     * @param int $amount
     * @param int $totalMoney
     * @return bool
     */
    private function validateAmountAndMoney(int $amount, int $totalMoney): bool
    {
        return $totalMoney >= $amount;
    }

    /**
     * @param int $amount
     * @param int $totalMoney
     * @return bool
     */
    private function mustReturnMoney(int $amount, int $totalMoney): bool
    {
        return $totalMoney > $amount;
    }

    /**
     * @param int $money
     * @param array $boxTotal
     * @return int[]
     * @throws BusinessLogicException
     */
    private function getReturnMoney(int $money, array $boxTotal): array
    {
        if ( !$this->isThereEnoughMoneyToReturn($money, $boxTotal)){
            throw new BusinessLogicException('no money in the box.');
        }

        return $this->calculateDenominationOfMoneyToReturn($money, $boxTotal);

    }

    /**
     * @param int $money
     * @param array $boxTotal
     * @return bool
     */
    private function isThereEnoughMoneyToReturn(int $money, array $boxTotal): bool
    {
        return $boxTotal['total_money'] >= $money;
    }

    /**
     * @param int $money
     * @param array $boxTotal
     * @return int[]
     */
    private function calculateDenominationOfMoneyToReturn(int $money, array $boxTotal): array
    {
        unset($boxTotal['total_money']);

        return $this->moneyService->getDenominationOfMoneyToReturn($money, $boxTotal);

    }

}
