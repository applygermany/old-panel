<?php

namespace App\Http\Resources\User;

use App\Models\User;
use App\Providers\JDF;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardAcceptanceResource extends JsonResource
{
    public function toArray($request)
    {
        $dataArray = [
            'admittance' => $this->admittance,
            'lastFormSubmit' => $this->last_form_submit,
            'birthDate' => $this->birth_date
        ];

        $user = User::find($this->user_id);
        if ($user->package_at !== null) {
            $date = explode(' ', $user->package_at);
            $date = explode('-', $date[0]);
            $date = JDF::gregorian_to_jalali($date[0], $date[1], $date[2], '/');
            $dataArray['date'] = $date;
        } else {
            $dataArray['date'] = '';
        }

        return $dataArray;
    }
}