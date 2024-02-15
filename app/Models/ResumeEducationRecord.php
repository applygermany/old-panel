<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeEducationRecord extends Model
{
    protected $guarded = [];

    protected $table = 'resume_education_records';
    public $timestamps = false;

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
