<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDuty extends Model
{
    use HasFactory;

    protected $fillable = ['title' , 'text' , 'status' , 'deadline', 'apply_level_id'];
    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getApplyLevelAttribute(){
        if($this->attributes['apply_level_id'] !== 0){
            return ApplyLevel::find($this->attributes['apply_level_id']);
        }
        return 0;
    }

}
