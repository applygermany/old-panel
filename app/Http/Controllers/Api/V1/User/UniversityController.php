<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Models\Option;
use Illuminate\Http\Request;
use App\Models\UserUniversity;
use App\Http\Controllers\Controller;
use App\Http\Services\V1\User\UniversityService;
use App\Http\Resources\User\UserUniversitiesResource;

class UniversityController extends Controller {
	protected $university;
	
	public function __construct(UniversityService $university) {
		$this->university = $university;
	}
	
	public function universities(Request $request) {
		$universities = $this->university->universities($request);
		return response([
			'status'                => 1,
			'msg'                   => 'لیست دانشگاه ها',
			'fields'                => $universities['fields'],
			'states'                => $universities['states'],
			'cities'                => $universities['cities'],
			'selected_universities' => count(UserUniversity::where("user_id", auth()->guard("api")->user()->id)->get()),
			'max_universities'      => auth()->guard("api")->user()->max_university_count,
			'geographicalLocations' => $universities['geographicalLocations'],
			'universities'          => UserUniversitiesResource::collection($universities['universities']),
		]);
	}
	
	public function chooseUniversity(Request $request) {
		$rules = [
			'id' => 'required|should_be_nums',
		];
		$validator = validator()->make($request->all(), $rules);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
			]);
		$chooseUniversity = $this->university->chooseUniversity($request);
		if ($chooseUniversity == 1)
			return response([
				'status' => 1,
				'msg'    => 'دانشگاه به سبد خرید اضافه شد',
			]);
		elseif ($chooseUniversity == 2)
			return response([
				'status' => 0,
				'msg'    => 'شما بیشتر از این مجاز به انتخاب دانشگاه نیستید',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در اضافه کردن دانشگاه',
		]);
	}
	
	public function applyStatus() {
		$universities = $this->university->applyStatus();
		return response([
			'status'       => 1,
			'msg'          => 'وضعیت اپلای',
			'universities' => UserUniversitiesResource::collection($universities),
		]);
	}
}
