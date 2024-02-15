<?php

namespace App\Models;

use App\Providers\MyHelpers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class ApplyPhase extends Authenticatable
{
    protected $guarded = [];

    public $table = "apply_phases";
}
