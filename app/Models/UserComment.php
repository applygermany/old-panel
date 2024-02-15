<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserComment extends Model
{
    use HasFactory;

    protected $fillable = ['text' , 'user_id'];
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    function getJalaliCreatedAttribute()
    {
        $date = explode(' ', $this->attributes['created_at']);
        $date = explode('-', $date[0]);
        $date = \App\Providers\JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
        return $date;
    }
}
