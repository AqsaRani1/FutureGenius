<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('course_events', function (Blueprint $table) {
        $table->string('file_path')->nullable();        // for assignment uploads
        $table->string('meeting_link')->nullable();
        $table->dateTime('end_date')->nullable();
        $table->dateTime('start')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_events', function (Blueprint $table) {
            //
        });
    }
};
