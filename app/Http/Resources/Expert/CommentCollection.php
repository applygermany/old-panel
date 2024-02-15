<?php

namespace App\Http\Resources\Expert;

use App\Providers\MyHelpers;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
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
            $data = [
                'id' => $item->id,
                'text' => $item->text,
                'score' => $item->score,
                'author' => new UserResource($item->userAuthor)
            ];

            $data['date'] = MyHelpers::dateToHuman($item->created_at);

            return $data;
        });
    }
}
