<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeWork extends Model
{
    protected $guarded = [];

    protected $table = 'resume_works';
    public $timestamps = false;

    public function resume()
    {
        return $this->belongsTo(Resume::class);
    }
}
