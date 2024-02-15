<?php
namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardSupervisorResource extends JsonResource
{
    public function toArray($request)
    {
        $dataArray = [
            'id' => $this->id,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname
        ];

        $dataArray['image'] = route('imageUser',['id'=>$this->id,'ua'=>strtotime($this->updated_at)]);

        return $dataArray;
    }
}