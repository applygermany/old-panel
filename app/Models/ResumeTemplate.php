<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeTemplate extends Model
{
    protected $guarded = [];

    public $timestamps = false;
    public $table = "resume_template";
    protected $casts = ["colors" => "array"];
}
