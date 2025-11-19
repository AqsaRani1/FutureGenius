<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_duration_to_quizzes_table.php
public function up()
{
    Schema::table('quizzes', function (Blueprint $table) {
        $table->integer('duration')->nullable()->comment('Duration in minutes');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            //
        });
    }
};
