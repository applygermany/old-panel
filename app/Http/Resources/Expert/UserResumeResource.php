<?php

namespace App\Http\Resources\Expert;

use App\Providers\JDF;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResumeResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request) {
		$data = [
			'id'        => $this->id,
			'email'     => $this->email,
			'mobile'    => $this->mobile,
			'firstname' => $this->firstname,
			'lastname'  => $this->lastname,
			'type'      => $this->type,
			'birthDate' => (strtotime($this->birth_date) > 0 ? date("Y/m/d", strtotime($this->birth_date)) : $this->birth_date),
			'city'      => $this->city,
			'acceptance' => NULL,
		];
		if ($this->acceptances->first()) {
            $data['acceptance'] = $this->acceptances->first();
            $data['acceptance']['created_at_jalali'] = JDF::jdate('Y/m/d h:i:s', strtotime($this->acceptances->first()->created_at));
        }
		$data['image'] = route('imageUser', ['id' => $this->id, 'ua' => strtotime($this->updated_at)]);
		return $data;
	}
}
