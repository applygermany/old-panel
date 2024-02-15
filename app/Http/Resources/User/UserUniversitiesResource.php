<?php

namespace App\Http\Resources\User;

use App\Models\Invoice;
use App\Models\Transaction;
use App\Providers\MyHelpers;
use Illuminate\Http\Resources\Json\JsonResource;

class UserUniversitiesResource extends JsonResource
{
    public function toArray($request)
    {
        $dataArray = [
            'id' => $this->id,
            'userId' => auth()->guard('api')->id(),
            'universityId' => $this->university_id,
            'field' => $this->field,
            'chanceGetting' => $this->chance_getting,
            'description' => $this->description,
            'offer' => $this->offer,
            'title' => $this->title,
            'city' => $this->city,
            'link' => $this->link,
            'status' => $this->status,
            'state' => $this->state,
            'geographicalLocation' => $this->geographical_location,
            'cityCrowd' => $this->city_crowd,
            'costLiving' => $this->cost_living,
            'levelStatus' => $this->level_status
        ];
        $dataArray['payed'] = 0;
        $dataArray['applyFile'] = 0;
        $dataArray['applyFileSize'] = 0;
        $file = public_path('uploads/applies/' . auth()->guard('api')->id() . '/' . $this->id . '.pdf');
        if (is_file($file)) {
            $dataArray['applyFile'] = route('applyFile', ['userId' => auth()->guard('api')->id(), 'id' => $this->id]);
            $dataArray['applyFileSize'] = MyHelpers::formatSizeUnits(filesize($file));
        }
        $transaction = Invoice::where('user_id', auth()->guard('api')->id())->where('invoice_type', 'final')->where('payment_status', 'paid')->first();
        if ($transaction)
            $dataArray['payed'] = 1;
        $dataArray['image'] = route('imageUniversity', ['id' => $this->university_id, 'ua' => strtotime($this->updated_at)]);
        $dataArray['logo'] = route('logoUniversity', ['id' => $this->university_id, 'ua' => strtotime($this->updated_at)]);
        return $dataArray;
    }
}