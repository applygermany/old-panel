<?php
namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class FirstDayTelSupportsResource extends JsonResource
{
    public function toArray($request)
    {
        $dataArray = [
            'id' => $this->id,
            'title' => '',
            'date' => $this->day_tel_fa,
            'fromTime' => $this->from_time,
            'toTime' => $this->to_time,
            'price' => $this->price,
            'reserved' => 2
        ];

        $telSupport = DB::select("select * from `nag_user_tel_supports` where tel_support_id = ".$this->id);
        if(count($telSupport) > 0) {
            $dataArray['reserved'] = 1;
            if($telSupport[0]->user_id == auth()->guard('api')->id()) {
                $dataArray['reserved'] = 3;
                $dataArray['title'] = $telSupport[0]->title;
            }
        }
        return $dataArray;
    }
}