<?php

namespace App\Http\Resources\Expert;

use Illuminate\Http\Resources\Json\JsonResource;

class UserSupervisorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return new UserResource($this->user);
    }
}
