<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Off extends Model
{
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function factors()
    {
        return $this->hasMany(Factor::class);
    }

    public function getOffTitleAttribute(){
        if($this->attributes['type'] == 'other')
            return "پیش پرداخت";
        elseif($this->attributes['type'] == 'resume')
            return "رزومه و انگیزه نامه";
        elseif($this->attributes['type'] == 'final')
            return "تسویه";
        elseif($this->attributes['type'] == 'tel-support')
            return "مشاوره تلفنی";
    }
}
