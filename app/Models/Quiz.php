<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model {
    protected $fillable = ['course_event_id', 'title', 'duration'];

    public function event() {
        return $this->belongsTo(CourseEvents::class, 'course_event_id');
    }

    public function questions() {
        return $this->hasMany(QuizQuestion::class);
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }
}