<?php

namespace App\Http\Resources\Expert;

use App\Providers\JDF;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'text' => $this->text,
            'owner' => $this->owner->firstname . ' ' . $this->owner->lastname,
            'image' => $this->owner ? route('imageUser', ['id' => $this->owner->id, 'ua' => strtotime($this->owner->updated_at)]) : '',
            'ownerId' => $this->owner_id
        ];

        $date = $this->created_at->format('Y-m-d');

        $date = explode('-', $date);
        $year = $date[0];
        $month = $date[1];
        $day = $date[2];

        $date = JDF::gregorian_to_jalali($year, $month, $day, '-');

        $data['date'] = $date;

        return $data;
    }
}
