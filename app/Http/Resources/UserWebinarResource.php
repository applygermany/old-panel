<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserWebinarResource extends JsonResource {
	public function toArray($request) {
		
		$dataArray = [
			'id'         => $this->id,
			'webinar_id' => $this->webinar_id,
			'name'       => $this->name,
			'family'     => $this->family,
			'price'      => $this->price,
			'mobile'     => $this->mobile,
			'email'      => $this->email,
			'field'      => $this->field,
			'grade'      => $this->grade,
			'instagram'  => $this->instagram,
			'telegram'   => $this->telegram,
		];
		return $dataArray;
	}
}
