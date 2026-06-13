<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AssignmentModuleController;
use App\Http\Controllers\AssignmentSubmissionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamSubmissionController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public routes
|--------------------------------------------------------------------------
*/

Route::post('/auth/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Authenticated routes (Sanctum Bearer token)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // ---------------------------------------------------------------
    // Auth
    // ---------------------------------------------------------------
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::patch('/auth/profile', [AuthController::class, 'updateProfile']);

    // ---------------------------------------------------------------
    // Users
    // ---------------------------------------------------------------
    Route::middleware('role:admin,lecturer')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });

    // ---------------------------------------------------------------
    // Faculties
    // ---------------------------------------------------------------
    Route::middleware('role:admin,lecturer')->group(function () {
        // Must be registered before /faculties/{faculty}
        Route::get('/faculties/available-for-dean', [FacultyController::class, 'availableForDean']);
        Route::get('/faculties', [FacultyController::class, 'index']);
        Route::get('/faculties/{faculty}', [FacultyController::class, 'show']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::post('/faculties', [FacultyController::class, 'store']);
        Route::put('/faculties/{faculty}', [FacultyController::class, 'update']);
        Route::delete('/faculties/{faculty}', [FacultyController::class, 'destroy']);
    });

    // ---------------------------------------------------------------
    // Departments
    // ---------------------------------------------------------------
    Route::middleware('role:admin,lecturer')->group(function () {
        Route::get('/departments', [DepartmentController::class, 'index']);
        Route::get('/departments/{department}', [DepartmentController::class, 'show']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::post('/departments', [DepartmentController::class, 'store']);
        Route::put('/departments/{department}', [DepartmentController::class, 'update']);
        Route::delete('/departments/{department}', [DepartmentController::class, 'destroy']);
    });

    // ---------------------------------------------------------------
    // Semesters
    // ---------------------------------------------------------------
    Route::get('/semesters', [SemesterController::class, 'index']);

    Route::middleware('role:admin')->group(function () {
        Route::post('/semesters', [SemesterController::class, 'store']);
        Route::put('/semesters/{semester}', [SemesterController::class, 'update']);
        Route::delete('/semesters/{semester}', [SemesterController::class, 'destroy']);
    });

    // ---------------------------------------------------------------
    // Subjects
    // ---------------------------------------------------------------
    Route::get('/subjects', [SubjectController::class, 'index']);
    Route::get('/subjects/{subject}', [SubjectController::class, 'show']);

    Route::middleware('role:admin')->group(function () {
        Route::post('/subjects', [SubjectController::class, 'store']);
        Route::put('/subjects/{subject}', [SubjectController::class, 'update']);
        Route::delete('/subjects/{subject}', [SubjectController::class, 'destroy']);
    });

    // ---------------------------------------------------------------
    // Classrooms
    // ---------------------------------------------------------------
    Route::get('/classrooms', [ClassroomController::class, 'index']);
    Route::get('/classrooms/{classroom}', [ClassroomController::class, 'show']);

    Route::middleware('role:admin')->group(function () {
        Route::post('/classrooms', [ClassroomController::class, 'store']);
    });

    // ---------------------------------------------------------------
    // Schedules
    // ---------------------------------------------------------------
    Route::get('/schedules', [ScheduleController::class, 'index']);

    Route::middleware('role:admin,lecturer')->group(function () {
        Route::post('/schedules', [ScheduleController::class, 'store']);
        Route::put('/schedules/{schedule}', [ScheduleController::class, 'update']);
        Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy']);
    });

    // ---------------------------------------------------------------
    // Assignments
    // ---------------------------------------------------------------
    Route::get('/assignments', [AssignmentController::class, 'index']);
    Route::get('/assignments/{assignment}', [AssignmentController::class, 'show']);

    Route::middleware('role:admin,lecturer')->group(function () {
        Route::post('/assignments', [AssignmentController::class, 'store']);
        Route::put('/assignments/{assignment}', [AssignmentController::class, 'update']);
        Route::delete('/assignments/{assignment}', [AssignmentController::class, 'destroy']);
        Route::delete('/assignment-modules/{module}', [AssignmentModuleController::class, 'destroy']);
        Route::get('/assignments/{assignment}/submissions', [AssignmentSubmissionController::class, 'submissions']);
        Route::post('/assignment-submissions/{submission}/grade', [AssignmentSubmissionController::class, 'grade']);
    });

    Route::middleware('role:student')->group(function () {
        Route::post('/assignments/{assignment}/submit', [AssignmentSubmissionController::class, 'submit']);
        Route::get('/assignments/{assignment}/my-submission', [AssignmentSubmissionController::class, 'mySubmission']);
    });

    // ---------------------------------------------------------------
    // Exams
    // ---------------------------------------------------------------
    Route::get('/exams', [ExamController::class, 'index']);
    Route::get('/exams/{exam}', [ExamController::class, 'show']);

    Route::middleware('role:admin,lecturer')->group(function () {
        Route::post('/exams', [ExamController::class, 'store']);
        Route::put('/exams/{exam}', [ExamController::class, 'update']);
        Route::delete('/exams/{exam}', [ExamController::class, 'destroy']);
        Route::get('/exams/{exam}/submissions', [ExamSubmissionController::class, 'submissions']);
    });

    Route::middleware('role:student')->group(function () {
        Route::post('/exams/{exam}/submit', [ExamSubmissionController::class, 'submit']);
        Route::get('/exams/{exam}/my-submission', [ExamSubmissionController::class, 'mySubmission']);
    });
});
