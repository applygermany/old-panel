<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelSupport extends Model
{
    protected $guarded = [];

    protected $table = 'tel_supports';

    protected $fillable = ['day_tel' , 'from_time' , 'to_time' , 'price' , 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userTell()
    {
        return $this->hasOne(UserTelSupport::class);
    }
}
