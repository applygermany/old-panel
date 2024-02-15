<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserUniversity extends Model
{
    public $timestamps = false;

    protected $fillable = ['id' , 'user_id' , 'university_id' , 'field' , 'chance_getting' , 'description' , 'offer' , 'state' , 'deadline'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    function getLevelStatusTitleAttribute()
    {
        return match ($this->attributes['level_status']) {
            1 => "دریافت مدارک در سایت",
            2 => "در دست اپلای",
            3 => "ارسال مدارک به دانشگاه",
            4 => "بررسی توسط دانشگاه",
            5 => "اخذ پذیرش",
            6 => "ریجکت شده است",
            0 => "ثبت نشده",
            default => $this->attributes['level_status'],
        };
    }
}
