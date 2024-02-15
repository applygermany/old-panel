<?php

namespace App\Http\Resources\Expert;

use App\Providers\JDF;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DutyCollection extends ResourceCollection
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
                'title' => $item->title,
                'text' => $item->text,
                'diff' => [],
                'status' => $item->status != 3 ? (time() > strtotime($item->deadline) ? 2 : 1 ) : $item->status,
                'deadline' => $item->deadline,

            ];

            $date = explode('-',$item->deadline);
            $year = $date[0];
            $month   = $date[1];
            $day  = $date[2];

            $diff =  strtotime($item->deadline) - time();
            $data["diff"] = [round($diff /(24*60*60)), round(($diff) % 24)];  
    
            $deadline = JDF::gregorian_to_jalali($year , $month , $day , '-');

            $data['deadlineJalali'] = $deadline;

            return $data;
        })->reverse();
    }
}
