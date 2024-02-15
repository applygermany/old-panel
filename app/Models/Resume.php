<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->hasMany(ResumeCourse::class);
    }

    public function educationRecords()
    {
        return $this->hasMany(ResumeEducationRecord::class);
    }

    public function hobbies()
    {
        return $this->hasMany(ResumeHobby::class);
    }

    public function languages()
    {
        return $this->hasMany(ResumeLanguage::class);
    }

    public function researchs()
    {
        return $this->hasMany(ResumeResearch::class);
    }

    public function softwareKnowledges()
    {
        return $this->hasMany(ResumeSoftwareKnowledge::class);
    }

    public function works()
    {
        return $this->hasMany(ResumeWork::class);
    }

    public function writer()
    {
        return $this->belongsTo(User::class, 'writer_id');
    }
}
