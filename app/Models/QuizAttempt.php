<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    protected $fillable = ['quiz_id', 'student_id', 'score', 'started_at'];

    protected $casts = [
        'started_at' => 'datetime',
    ];

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
