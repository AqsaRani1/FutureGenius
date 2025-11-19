<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            // Drop wrong foreign key
            $table->dropForeign(['assignment_id']);

            // Rename column assignment_id → event_id
            $table->renameColumn('assignment_id', 'event_id');
        });

        Schema::table('assignment_submissions', function (Blueprint $table) {
            // Add NEW foreign key to course_events
            $table->foreign('event_id')
                  ->references('id')
                  ->on('course_events')
                  ->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            // Reverse foreign key update
            $table->dropForeign(['event_id']);
            $table->renameColumn('event_id', 'assignment_id');
        });

        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->foreign('assignment_id')
                  ->references('id')
                  ->on('assignments')
                  ->cascadeOnDelete();
        });
    }
};