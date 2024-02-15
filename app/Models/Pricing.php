<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    protected $guarded = [];

    public $table = 'pricing';
    public $timestamps = false;
}
