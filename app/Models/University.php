<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class University extends Model
{


    protected $guarded = [];

    protected $appends = array('acceptance_logo');
    public function getAcceptanceLogoAttribute()
    {
        return route('logoAcceptanceUniversity', ["id" => $this->id, "ua" => time()]);
    }
    
}
