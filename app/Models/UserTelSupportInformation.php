<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTelSupportInformation extends Model
{
    protected $table = 'user_tel_support_informations';
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userTelSupport()
    {
        return $this->belongsTo(UserTelSupport::class);
    }

    public function telSupport()
    {
        return $this->belongsTo(TelSupport::class);
    }
}
