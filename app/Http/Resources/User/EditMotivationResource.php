<?php
namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class EditMotivationResource extends JsonResource
{
    public function toArray($request)
    {
        $dataArray = [
            'id' => $this->id,
            'to' => $this->to,
            'country' => $this->country,
            'price' => $this->price,
            'name' => $this->name,
            'family' => $this->family,
            'phone' => $this->phone,
            'birthDate' => $this->birth_date,
            'birthPlace' => $this->birth_place,
            'email' => $this->email,
            'address' => $this->address,
            'about' => $this->about,
            'resume' => $this->resume,
            'whyGermany' => $this->why_germany,
            'afterGraduation' => $this->after_graduation,
            'extraText' => $this->extra_text,
            'adminMessage' => $this->admin_message
        ];

        if($this->to == 1)
            $dataArray['to'] = 'سفارت';
        else
            $dataArray['to'] = 'دانشگاه';

        if($this->country == 1)
            $dataArray['country'] = 'ایران';
        else
            $dataArray['country'] = 'کشورهای دیگر';

        $dataArray['image'] = route('imageMotivation',['id'=>$this->id]);
        $dataArray['file'] = route('motivation',['id'=>$this->id]);

        return $dataArray;
    }
}