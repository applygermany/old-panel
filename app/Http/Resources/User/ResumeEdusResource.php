<?php
namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class ResumeEdusResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'grade' => $this->grade,
            'fromDateYear' => $this->from_date_year,
            'fromDateMonth' => $this->from_date_month,
            'toDateYear' => $this->to_date_year,
            'toDateMonth' => $this->to_date_Month,
            'schoolName' => $this->school_name,
            'field' => $this->field,
            'gradeScore' => $this->grade_score,
            'city' => $this->city,
            'text' => $this->text
        ];
    }
}