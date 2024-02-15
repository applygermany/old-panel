<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivationUniversity extends Model
{
    protected $guarded = [];

    public $timestamps = false;
    protected $table = 'motivation_universities';

    public function motivation()
    {
        return $this->belongsTo(Motivation::class);
    }
}
