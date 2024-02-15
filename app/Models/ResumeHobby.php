<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeHobby extends Model
{
    protected $guarded = [];

    protected $table = 'resume_hobbies';
    public $timestamps = false;

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
