<?php

namespace App\Http\Resources\Expert;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserUniversityCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item) {
            $data = [
                'id' => $item->id,
                'university' => $item->university,
                'field' => $item->field,
                'chanceGetting' => $item->chance_getting,
                'description' => $item->description,
                'offer' => $item->offer,
                'image' => "",
                'state' => $item->state,
                'link' => $item->link,
                'deadline' => $item->deadline,
                'levelStatus' => $item->level_status
            ];
            $data['image'] = route('imageUniversity', ['id' => $item->university_id, 'ua' => strtotime($item->university->updated_at)]);
            return $data;
        });
    }
}
