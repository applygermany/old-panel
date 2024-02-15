<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeCourse extends Model
{
    protected $guarded = [];

    protected $table = 'resume_courses';
    public $timestamps = false;

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
