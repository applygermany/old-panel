<?php

namespace App\Http\Resources\User;

use App\Models\User;
use App\Models\Votes;
use App\Providers\MyHelpers;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardUserResource extends JsonResource {
	public function toArray($request) {
		$dataArray = [
			'upload_access' => $this->upload_access,
			'id'             => $this->id,
			'email'          => $this->email,
			'mobile'         => $this->mobile,
			'firstname'      => $this->firstname,
			'lastname'       => $this->lastname,
			'firstnameEn'    => $this->firstname_en,
			'lastnameEn'     => $this->lastname_en,
			'codemelli'      => $this->codemelli,
			'birthDate'      => NULL,
			'type'           => $this->type,
			'seenModalCount' => $this->has_seen_modal,
			'tel_support_flag' => $this->tel_support_flag,
			'balance' => $this->balance,
			'contractType' => $this->contractType,
			'votes'          => Votes::where('user_id', $this->id)->get(),
		];
		$user = User::find($this->id);
		$user->has_seen_modal = $user->has_seen_modal + 1;
		$user->save();
		if ($this->birth_date != NULL)
			$dataArray['birthDate'] = MyHelpers::dateToJalali($this->birth_date);
		$dataArray['image'] = route('imageUser', ['id' => $this->id, 'ua' => strtotime($this->updated_at)]);
		$dataArray['hasImage'] = is_file(public_path('uploads/avatar/' . $this->id . '.jpg'));
		return $dataArray;
	}
}
