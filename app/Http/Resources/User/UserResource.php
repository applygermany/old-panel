<?php
namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $dataArray = [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname
        ];

        $dataArray['image'] = route('imageUser',['id'=>$this->id,'ua'=>strtotime($this->updated_at)]);

        return $dataArray;
    }
}