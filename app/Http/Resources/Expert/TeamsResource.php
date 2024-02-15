<?php

namespace App\Http\Resources\Expert;

use Illuminate\Http\Resources\Json\JsonResource;

class TeamsResource extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'field' => $this->field
        ];
        $data['image'] = route('imageTeam',['id'=>$this->id,'ua'=>strtotime($this->updated_at)]);

        return $data;
    }
}
