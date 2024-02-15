<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplyLevel extends Model
{
    protected $guarded = [];

    protected $table = 'apply_levels';
    public function titles(){
        return $this->hasMany(ApplyLevelTitle::class);
    }
}
