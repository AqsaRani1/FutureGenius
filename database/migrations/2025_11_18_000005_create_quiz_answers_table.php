<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_attempt_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_question_id')->constrained()->onDelete('cascade');
            $table->foreignId('quiz_option_id')->nullable()->constrained()->onDelete('set null');
            $table->text('answer_text')->nullable(); // for short answer
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('quiz_answers');
    }
};