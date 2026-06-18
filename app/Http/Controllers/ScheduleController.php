<?php

namespace App\Http\Controllers;

use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Services\ScheduleService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ScheduleController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ScheduleService $scheduleService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->success(
            ScheduleResource::collection($this->scheduleService->list($request->user()))
        );
    }

    public function show(int $schedule): JsonResponse
    {
        $found = $this->scheduleService->findById($schedule);

        return $this->success(ScheduleResource::make($found));
    }

    public function store(StoreScheduleRequest $request): JsonResponse
    {
        $schedule = $this->scheduleService->create($request->validated());

        return $this->success(ScheduleResource::make($schedule), 'Schedule created', 201);
    }

    public function update(StoreScheduleRequest $request, int $schedule): JsonResponse
    {
        $updated = $this->scheduleService->update($schedule, $request->validated());

        return $this->success(ScheduleResource::make($updated), 'Schedule updated');
    }

    public function destroy(int $schedule): JsonResponse
    {
        $this->scheduleService->delete($schedule);

        return $this->success(null, 'Schedule deleted');
    }
}
