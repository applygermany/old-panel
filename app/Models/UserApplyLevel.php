<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserApplyLevel extends Model
{
    public $timestamps = false;
    protected $table = 'user_apply_levels';
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
