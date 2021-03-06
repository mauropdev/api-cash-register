<?php

namespace App\Http\Controllers;


use App\Exceptions\BusinessLogicException;
use App\Http\Requests\MovementCreateRequest;
use App\Http\Requests\MovementRequest;
use App\Repositories\MovementRepository;
use App\Services\MovementService;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MovementController extends Controller
{
    use ApiResponser;

    /**
     * @var movementService
     */
    private MovementService $movementService;

    /**
     * @var MovementRepository
     */
    private MovementRepository $movementRepository;

    /**
     * MovementController constructor.
     * @param MovementService $movementService
     * @param MovementRepository $movementRepository
     */
    public function __construct(MovementService $movementService, MovementRepository $movementRepository)
    {
        $this->movementService = $movementService;
        $this->movementRepository = $movementRepository;
    }

    /**
     *
     * @param MovementRequest $movementRequest
     * @return JsonResponse
     */
    public function loadBaseToBox(MovementRequest $movementRequest): JsonResponse
    {
        $loadBase = $this->movementService->loadBaseToBox($movementRequest->all());
        return $this->successResponse($loadBase,Response::HTTP_CREATED);
    }

    /**
     * @return JsonResponse
     * @throws BusinessLogicException
     */
    public function unloadBaseToBox(): JsonResponse
    {
        return $this->successResponse($this->movementService->unloadBaseToBox());
    }

    /**
     * @return JsonResponse
     */
    public function getStatusBox(): JsonResponse
    {
        return $this->successResponse($this->movementRepository->getStatusBox());
    }

    /**
     * @return JsonResponse
     */
    public function getEventLogs(): JsonResponse
    {
        return $this->successResponse($this->movementRepository->getEventLogs());
    }

    /**
     * @param MovementRequest $movementRequest
     * @return JsonResponse
     */
    public function getCustomBoxStatus(MovementRequest $movementRequest): JsonResponse
    {
        return $this->successResponse($this->movementRepository->getEventLogs($movementRequest->all()));
    }

    /**
     *
     * @param MovementCreateRequest $movementCreateRequest
     * @return JsonResponse
     * @throws BusinessLogicException
     */
    public function makePayment(MovementCreateRequest $movementCreateRequest): JsonResponse
    {
        $data = $movementCreateRequest->all();
        $loadBase = $this->movementService->makePayment($data);
        return $this->successResponse($loadBase,Response::HTTP_CREATED);
    }
}
