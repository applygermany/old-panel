<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeLanguage extends Model
{
    protected $guarded = [];

    protected $table = 'resume_languages';
    public $timestamps = false;

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
