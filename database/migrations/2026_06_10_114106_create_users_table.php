<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image')->nullable();

            // 1: Admin | 2: Lecturer | 3: Student
            $table->unsignedTinyInteger('role')->default(3);

            // dean | department_head | lecturer | student
            $table->string('position')->nullable();

            $table->string('nidn')->nullable();
            $table->string('nim')->nullable();

            $table->foreignId('subject_id')->nullable()->constrained('subjects');
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->foreignId('faculty_id')->nullable()->constrained('faculties');
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
