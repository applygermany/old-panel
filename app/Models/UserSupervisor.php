<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSupervisor extends Model
{
    public $timestamps = false;
    protected $table = 'user_supervisors';
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class,'supervisor_id');
    }
}
