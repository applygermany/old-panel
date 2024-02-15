<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardUsersResource extends JsonResource {
	public function toArray($request) {
		$dataArray = [
			'id'        => $this->id,
			'firstname' => $this->firstname,
			'lastname'  => $this->lastname,
		];
		$invite = auth()->guard("api")->user()->invites()->where('id', $this->id)->orderBy('id', 'DESC')->first();
		if ($invite->invoices()->where('invoice_title', 'receipt')->first()) {
			$dataArray['level'] = [
				'key'   => 4,
				'title' => 'تسویه حساب',
			];
		} elseif ($invite->userUniversities()->where('status', 2)->first()) {
			$dataArray['level'] = [
				'key'   => 3,
				'title' => 'اخذ پذیرش',
			];
		} elseif ($invite->userUniversities()->first()) {
			$dataArray['level'] = [
				'key'   => 2,
				'title' => 'در دست اپلای',
			];
		} elseif ($invite->acceptances()->first()) {
			$dataArray['level'] = [
				'key'   => 1,
				'title' => 'درخواست اخذ پذیرش',
			];
		} else {
			$dataArray['level'] = [
				'key'   => 0,
				'title' => 'ثبت نام در پورتال',
			];
		}
		$dataArray['image'] = route('imageUser', ['id' => $this->id, 'ua' => strtotime($this->updated_at)]);
		return $dataArray;
	}
}
