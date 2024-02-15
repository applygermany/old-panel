<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTelSupport extends Model
{
    protected $table = 'user_tel_supports';
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class,'supervisor_id');
    }

    public function telSupport()
    {
        return $this->belongsTo(TelSupport::class);
    }

    public function telSupportInformation(){
        return UserTelSupportInformation::where('user_tel_support_information', $this->attributes['id'])->first();
    }
}
