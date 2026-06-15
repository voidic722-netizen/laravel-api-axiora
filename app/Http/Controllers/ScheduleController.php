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

    /**
     * GET /api/schedules
     */
    public function index(Request $request): JsonResponse
    {
        return $this->success(
            ScheduleResource::collection($this->scheduleService->list($request->user()))
        );
    }

    /**
     * POST /api/schedules
     */
    public function store(StoreScheduleRequest $request): JsonResponse
    {
        $schedule = $this->scheduleService->create($request->validated());

        return $this->success(ScheduleResource::make($schedule), 'Schedule created', 201);
    }

    /**
     * PUT /api/schedules/{schedule}
     */
    public function update(StoreScheduleRequest $request, int $schedule): JsonResponse
    {
        $updated = $this->scheduleService->update($schedule, $request->validated());

        return $this->success(ScheduleResource::make($updated), 'Schedule updated');
    }

    /**
     * DELETE /api/schedules/{schedule}
     */
    public function destroy(int $schedule): JsonResponse
    {
        $this->scheduleService->delete($schedule);

        return $this->success(null, 'Schedule deleted');
    }
}
