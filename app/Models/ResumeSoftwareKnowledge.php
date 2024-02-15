<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeSoftwareKnowledge extends Model
{
    protected $guarded = [];

    protected $table = 'resume_software_knowledges';
    public $timestamps = false;

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
