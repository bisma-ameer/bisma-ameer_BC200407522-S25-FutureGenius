<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('course_enrollments', function (Blueprint $table) {
            // Agar enrolled_at column nahi hai tou add karein
            if (!Schema::hasColumn('course_enrollments', 'enrolled_at')) {
                $table->timestamp('enrolled_at')->useCurrent();
            }
            
            // Agar column hai par datetime nahi hai tou change karein
            $table->timestamp('enrolled_at')->useCurrent()->change();
        });
    }

    public function down()
    {
        Schema::table('course_enrollments', function (Blueprint $table) {
            // Rollback logic
        });
    }
};