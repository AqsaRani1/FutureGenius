<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class courseContents extends Model
{
    protected $fillable = ['course_id', 'title', 'description', 'file_path'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
