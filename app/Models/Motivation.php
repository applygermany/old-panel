<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motivation extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function universities()
    {
        return $this->hasMany(MotivationUniversity::class);
    }

    public function writer()
    {
        return $this->belongsTo(User::class, 'writer_id');
    }
}
