<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseEvents extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'event_date',
        'type',
        'file_path',
        'meeting_link',
        'end_date',
        'start'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class, 'event_id');
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class, 'course_event_id');
    }
}
