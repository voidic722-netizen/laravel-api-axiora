<?php

namespace App\Providers;

use App\Repositories\AssignmentRepository;
use App\Repositories\AssignmentSubmissionRepository;
use App\Repositories\AuthRepository;
use App\Repositories\ClassroomRepository;
use App\Repositories\Contracts\AssignmentRepositoryInterface;
use App\Repositories\Contracts\AssignmentSubmissionRepositoryInterface;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\Contracts\ClassroomRepositoryInterface;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use App\Repositories\Contracts\ExamRepositoryInterface;
use App\Repositories\Contracts\ExamSubmissionRepositoryInterface;
use App\Repositories\Contracts\FacultyRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use App\Repositories\Contracts\SemesterRepositoryInterface;
use App\Repositories\Contracts\SubjectRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\DepartmentRepository;
use App\Repositories\ExamRepository;
use App\Repositories\ExamSubmissionRepository;
use App\Repositories\FacultyRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\SemesterRepository;
use App\Repositories\SubjectRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        AuthRepositoryInterface::class                 => AuthRepository::class,
        UserRepositoryInterface::class                 => UserRepository::class,
        FacultyRepositoryInterface::class              => FacultyRepository::class,
        DepartmentRepositoryInterface::class           => DepartmentRepository::class,
        SemesterRepositoryInterface::class             => SemesterRepository::class,
        SubjectRepositoryInterface::class              => SubjectRepository::class,
        ClassroomRepositoryInterface::class            => ClassroomRepository::class,
        ScheduleRepositoryInterface::class             => ScheduleRepository::class,
        AssignmentRepositoryInterface::class           => AssignmentRepository::class,
        AssignmentSubmissionRepositoryInterface::class => AssignmentSubmissionRepository::class,
        ExamRepositoryInterface::class                 => ExamRepository::class,
        ExamSubmissionRepositoryInterface::class       => ExamSubmissionRepository::class,
    ];
}
