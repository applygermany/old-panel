<?php

namespace App\Models;

use Exception;

use Illuminate\Database\Eloquent\Model;


class NewAccepted extends Model
{
    protected $guarded = [];

    public $timestamps = false;
    protected $casts = [
        'universities' => 'array',
        'acceptance_images' => 'array',
    ];
    protected $appends = array( 'visa_image', 'university');
    public function getUniversityAttribute()
    {
        $universities = $this->universities;
        $i = 0;
        foreach ($universities as &$university) {
            $i++;
            $uni = University::find($university["id"]);
            $university["title"] = $uni->title; 
            $university["logo"] = $uni->acceptance_logo; 

            if(is_file(public_path('uploads/acepted/' . $this->id  . "_acceptance_{$i}.jpg"))){
                $university["acceptance"] =  route('imageAcceptance', ['id' => $this->id, 'pos' => $i]);
            }else{
                $university["acceptance"] = ""; 
            }
            
        }
        return $universities;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getVisaImageAttribute()
    {
	    if (is_file(public_path('uploads/acepted/' . $this->id . '_visa.jpg'))) {
		    return route('imageVisa', ['id' => $this->id]);
	    } else {
	    	return  NULL;
	    }
    }
}
