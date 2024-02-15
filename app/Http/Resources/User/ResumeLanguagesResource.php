<?php
namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class ResumeLanguagesResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'fluencyLevel' => $this->fluency_level,
            'degree' => $this->degree,
            'score' => $this->score
        ];
    }
}