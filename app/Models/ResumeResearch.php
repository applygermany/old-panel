<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeResearch extends Model
{
    protected $guarded = [];

    protected $table = 'resume_researchs';
    public $timestamps = false;

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
