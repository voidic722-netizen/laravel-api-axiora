<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * F1: enables Cloudinary cleanup when an assignment module is deleted.
     */
    public function up(): void
    {
        Schema::table('assignment_modules', function (Blueprint $table) {
            $table->string('cloudinary_public_id')->nullable()->after('file_path');
        });
    }

    public function down(): void
    {
        Schema::table('assignment_modules', function (Blueprint $table) {
            $table->dropColumn('cloudinary_public_id');
        });
    }
};
