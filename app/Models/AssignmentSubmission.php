<?php
// app/Models/AssignmentSubmission.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'student_id',
        'file',
        'answer_text',
         'grade',
    'feedback',
    ];

  public function event()
{
    return $this->belongsTo(CourseEvents::class, 'event_id');
}


    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

}