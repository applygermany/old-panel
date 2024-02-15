<?php

namespace App\Http\Resources\Expert;

use App\Providers\JDF;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserTelSupportCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($item){
            $data = [
                'id' => $item->id,
                'user' => new UserResource($item->user),
                'advise' => $item->advise,
                'fromTime' => $item->telSupport->from_time,
                'toTime' => $item->telSupport->to_time,
            ];

            $date = explode('-',$item->tel_date);
            $year = $date[0];
            $month   = $date[1];
            $day = $date[2];

            $tel_date = JDF::gregorian_to_jalali($year , $month , $day , '-');

            $data['telDate'] = $tel_date;

            return $data;
        });
    }
}
