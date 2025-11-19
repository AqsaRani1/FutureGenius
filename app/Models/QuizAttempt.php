<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model {
    protected $fillable = ['quiz_id', 'student_id', 'score', 'started_at', 'question_started_at'];

    public function answers() {
        return $this->hasMany(QuizAnswer::class);
    }

    public function student() {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }
}
