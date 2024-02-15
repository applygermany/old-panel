<?php
namespace App\Http\Resources\Expert;

use Illuminate\Http\Resources\Json\JsonResource;

class TelSupportTagResource extends JsonResource
{
    public function toArray($request)
    {
        $dataArray = [
            'title' => $this->title,
            'value' => $this->value
        ];

       if($this->type == 1) {
           $date = date('Y-m-d',strtotime(date('Y-m-d') . $this->value." days"));
           $dataArray['value'] = $date;
       }

        return $dataArray;


    }
}