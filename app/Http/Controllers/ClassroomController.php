<?php

namespace App\Http\Controllers;

use App\Http\Requests\Classroom\StoreClassroomRequest;
use App\Http\Resources\ClassroomDetailResource;
use App\Http\Resources\ClassroomResource;
use App\Services\ClassroomService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClassroomController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ClassroomService $classroomService,
    ) {
    }

    /**
     * GET /api/classrooms
     * Students see only their own classroom.
     */
    public function index(Request $request): JsonResponse
    {
        return $this->success(
            ClassroomResource::collection($this->classroomService->list($request->user()))
        );
    }

    /**
     * GET /api/classrooms/{classroom}
     */
    public function show(Request $request, int $classroom): JsonResponse
    {
        $detail = $this->classroomService->detail($classroom, $request->user());

        return $this->success(ClassroomDetailResource::make($detail));
    }

    /**
     * POST /api/classrooms
     */
    public function store(StoreClassroomRequest $request): JsonResponse
    {
        $classroom = $this->classroomService->create($request->validated());

        return $this->success(ClassroomResource::make($classroom), 'Classroom created', 201);
    }
}
