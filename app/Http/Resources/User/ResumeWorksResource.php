<?php
namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class ResumeWorksResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fromDateYear' => $this->from_date_year,
            'fromDateMonth' => $this->from_date_month,
            'toDateYear' => $this->to_date_year,
            'toDateMonth' => $this->to_date_Month,
            'companyName' => $this->company_name,
            'position' => $this->position,
            'city' => $this->city,
            'text' => $this->text
        ];
    }
}