<?php
namespace App\Http\Resources\User;

use App\Providers\JDF;
use Illuminate\Http\Resources\Json\JsonResource;

class NextTelSupportResource extends JsonResource
{
    public function toArray($request)
    {
        $dataArray = [
            'id' => $this->tel_support_id,
            'title' => $this->title,
            'date' => $this->telSupport->day_tel_fa,
            'fromTime' => $this->telSupport->from_time,
            'toTime' => $this->telSupport->to_time,
            'price' => $this->telSupport->price,
            'reserved' => 3
        ];
        $starttime = strtotime(date('Y-m-d H:i:s'));
        $totime = strtotime($this->telSupport->day_tel." ".$this->telSupport->from_time.":00");
        $miutes = round(abs($totime - $starttime) / 60);
        $h = 0;
        if($miutes > 60)
            $h = round($miutes/60,0);
        $m = round($miutes%60,0);
        $dataArray['hr'] = $h;
        $dataArray['mr'] = $m;
        $date = explode('-',$this->telSupport->day_tel_fa);
        $month = JDF::jdate_words(['mm'=>$date[1]]);
        $dataArray['date'] = $date[2]." ".$month['mm']." ".$date[0];
        return $dataArray;
    }
}