<?php

// database/migrations/2025_01_01_000001_create_assignment_submissions.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assignment_id');
            $table->unsignedBigInteger('student_id');
            $table->string('file')->nullable();  // assignment upload
            $table->longText('answer_text')->nullable(); // written answer
            $table->timestamps();
$table->integer('grade')->nullable();  // out of 100
$table->text('feedback')->nullable();
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
