<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\University;

class UserExtraUniversity extends Model
{
    protected $table='user_extra_universities';
    use HasFactory;
//    protected $with=['university'];
    public function university(){
        return $this->belongsTo(University::class,'university_id');
    }
}
