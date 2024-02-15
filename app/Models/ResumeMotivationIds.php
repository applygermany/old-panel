<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeMotivationIds extends Model
{
    protected $guarded = [];

    protected $table = 'resume_motivation_ids';


    function getDataAttribute(){
        if($this->attributes['model_type'] === 'resume')
            return Resume::find($this->attributes['model_id']);
        else
            return Motivation::find($this->attributes['model_id']);
    }
}