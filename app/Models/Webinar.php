<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Webinar extends Model
{
    use Sluggable;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'slug',
            ],
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    protected $appends = array('webinar_image', 'organizer_image');
    protected $guarded = [];

    public function getWebinarImageAttribute(){
        return route('imageWebinar',['id'=>$this->id,'ua'=>strtotime($this->updated_at)]);
    }
    public function getOrganizerImageAttribute(){
        return route('imageWebinarOrganizer',['id'=>$this->id,'ua'=>strtotime($this->updated_at)]);
    }
    public function users(){
        return $this->hasMany(UserWebinar::class);
    }

}
