<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpertTag extends Model
{
    public $timestamps = false;
    protected $table = 'expert_tags';
    protected $guarded = [];


    public function expert()
    {
        return $this->belongsTo(User::class);
    }
}
