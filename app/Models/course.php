<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'duration',
    ];

    // ✅ Instructors for a course
    public function instructors()
    {
        return $this->belongsToMany(User::class, 'course_instructor', 'course_id', 'instructor_id');
    }

    // ✅ Students enrolled in a course
    public function students()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id');
    }
    public function contents()
    {
        return $this->hasMany(courseContents::class);
    }

    public function events()
    {
        return $this->hasMany(CourseEvents::class);
    }


}