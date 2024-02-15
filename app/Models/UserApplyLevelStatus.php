<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserApplyLevelStatus extends Model
{
    public $timestamps = false;
    protected $table = 'user_apply_level_status';
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
