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
        $table->enum('type', ['assignment', 'quiz', 'live_session', 'other'])
              ->default('other')
              ->after('description');
    });
}

public function down(): void
{
    Schema::table('course_events', function (Blueprint $table) {
        $table->dropColumn('type');
    });
}
};