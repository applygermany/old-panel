<?php

namespace App\Http\Services\V1;

use App\Models\Faq;
use App\Models\Team;
use App\Models\Setting;
use App\Models\Webinar;
use App\Models\UserWebinar;
use App\Providers\MyHelpers;
use Illuminate\Http\Request;
use App\Models\Collaboration;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\WebinarResource;
use App\Http\Resources\UserWebinarResource;
use App\Http\Services\V1\User\InvoiceService;

class SiteService {
	public function settings() {
		$settings = Setting::find(1);
		return $settings;
	}
	
	public function teams() {
		$teams = Team::orderBy('ordering', 'asc')->get();
		return $teams;
	}
	
	public function faqs(Request $request) {
		$faq = Faq::Query();
		if ($request->question)
			$faq->where('question', 'LIKE', '%' . $request->question . '%');
		$faqs = $faq->where('type', 1)->orderBy('ordering', 'ASC')->get();
		return $faqs;
	}
	
	public function cfaqs(Request $request) {
		$faq = Faq::Query();
		if ($request->question)
			$faq->where('question', 'LIKE', '%' . $request->question . '%');
		$faqs = $faq->where('type', 2)->orderBy('ordering', 'ASC')->get();
		return $faqs;
	}
	
	public function webinar(Request $request) {
		$webinar = Webinar::where(DB::raw('slug'), $request->id)->where('status', 'active')->first();
		if ($webinar)
			return $webinar;
		return 0;
	}
	
	public function webinarId($id) {
		$webinar = Webinar::where('id', $id)->first();
		if ($webinar)
			return $webinar;
		return 0;
	}
	
	public function userWebinar(Request $request) {
		$webinar = UserWebinar::where('id', $request->id)->first();
		if ($webinar)
			return $webinar;
		return 0;
	}
	
	
	public function webinarBanners() {
		$webinar = Webinar::where('status', 'active')->get();
		if ($webinar) {
			
			return $webinar->filter(function ($item) {
				return is_file(public_path('uploads/webinar/' . $item->id . '_banner.jpg'));
			});
		}
		return 0;
	}
	
	public function uploadWebinarReceipt(Request $request) {
		if ($request->file('file')) {
			$filename = MyHelpers::generateRandomString(10) . time();
			$folder = '/uploads/webinarReceipt/';
			$file = $request->file('file');
			$file->move(public_path() . $folder, $filename . '.jpg');
			return $filename;
		}
		return 0;
	}
	
	public function submitWebinar(Request $request) {
		$webinar = Webinar::find($request->id);
		if (!($webinar->price > 0)) {
			if (!is_file(public_path('uploads/webinarReceipt/' . $request->imageName . '.jpg')))
				return 2;
		}
		$userWebinar = new UserWebinar();
		$userWebinar->webinar_id = $request->id;
		$userWebinar->name = $request->name;
		$userWebinar->family = $request->family;
		$userWebinar->price = $request->price;
		$userWebinar->mobile = $request->mobile;
		$userWebinar->email = $request->email;
		$userWebinar->field = $request->field;
		$userWebinar->grade = $request->grade;
		$userWebinar->instagram = $request->instagram;
        $userWebinar->introduction = $request->method_of_introduction;
		$userWebinar->telegram = $request->telegram;
		$userWebinar->payed = $request->price > 0 ? -1 : 1;
		
		if ($userWebinar->save()) {
			if (!($webinar->price > 0)) {
				rename(public_path('uploads/webinarReceipt/' . $request->imageName . '.jpg'), public_path('uploads/webinarReceipt/' . $userWebinar->id . '.jpg'));
			} else {
				$webinar = Webinar::find($request->id);
				if ($webinar->price > 0) {
					$invoice = new InvoiceService();
					$action = $invoice->goPay(0, $webinar->price, 5, $userWebinar->id);
					return  [
						'webinar' => new WebinarResource($webinar),
						'userWebinar' => new UserWebinarResource($userWebinar),
					];
					return [
						'type'   => 'paid',
						'transaction_id' => array_reverse(explode("/", $action))[0],
					];
				} else {
					return  [
						'webinar' => new WebinarResource($webinar),
						'userWebinar' => new UserWebinarResource($userWebinar),
					];
				}
			}
		}
		return  [
			'webinar' => new WebinarResource($webinar),
			'userWebinar' => new UserWebinarResource($userWebinar),
		];
		return 0;
	}
	
	public function uploadResumeCollaboration(Request $request) {
		if ($request->file('file')) {
			$filename = MyHelpers::generateRandomString(10) . time();
			$folder = '/uploads/resumeCollaboration/';
			$file = $request->file('file');
			$file->move(public_path() . $folder, $filename . '.pdf');
			return $filename;
		}
		return 0;
	}
	
	public function sendCollaboration(Request $request) {
//		if (!is_file(public_path('uploads/resumeCollaboration/' . $request->resumeName . '.pdf')))
//			return 2;
		$collaboration = new Collaboration();
		$collaboration->name = $request->name;
		$collaboration->family = $request->family;
		$collaboration->email = $request->email;
		$collaboration->field = $request->field;
		$collaboration->text = $request->text;
		$collaboration->birth_date = $request->birthDate;
		if ($collaboration->save()) {
			rename(public_path('uploads/resumeCollaboration/' . $request->resumeName . '.pdf'), public_path('uploads/resumeCollaboration/' . $collaboration->id . '.pdf'));
			
			return 1;
		}
		return 0;
	}
	
}
