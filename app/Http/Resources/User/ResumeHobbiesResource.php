<?php
namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class ResumeHobbiesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title
        ];
    }
}