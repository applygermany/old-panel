<?php

namespace App\Http\Resources\Expert;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UniversityCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            return [
                'id' => $item->id,
                'title' => $item->title,
                'city' => $item->city,
                'image' => route('imageUniversity',['id'=>$item->id,'ua'=>strtotime($item->updated_at)]),
                'state' => $item->state,
                'geographicalLocation' => $item->geographical_location,
                'cityCrowd' => $item->city_crowd,
                'description' => $item->description,
                'link' => $item->link,
                'costLiving' => $item->cost_living
            ];
        });
    }
}
