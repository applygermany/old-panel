<?php
namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class EditMotivationUniversitiesResource extends JsonResource
{
    public function toArray($request)
    {
        $dataArray = [
            'id' => $this->id,
            'name' => $this->name,
            'field' => $this->field,
            'grade' => $this->grade,
            'language' => $this->language,
            'text1' => $this->text1,
            'text2' => $this->text2
        ];

        return $dataArray;
    }
}