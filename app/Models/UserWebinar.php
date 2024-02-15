<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWebinar extends Model
{
    protected $table = 'user_webinars';
    protected $guarded = [];

    public function webinar(){
        return $this->belongsTo(Webinar::class);
    }
}
