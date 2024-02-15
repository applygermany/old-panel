<?php

namespace App\Http\Controllers\Api\V1\Expert;

use App\Providers\SMS;
use App\Models\University;
use App\Http\Controllers\Controller;
use App\Http\Resources\Expert\UniversityCollection;
use App\Http\Resources\Expert\UserResource;
use App\Http\Resources\Expert\UserUniversityCollection;
use App\Http\Services\V1\Expert\UniversityService;
use App\Models\User;
use App\Models\UserUniversity;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UniversityController extends Controller {
	protected $universityService;
	
	public function __construct(UniversityService $universityService) {
		$this->universityService = $universityService;
	}
	
	// get all universities
	public function getAllUniversities(Request $request) {
		$universities = $this->universityService->getAllUniversities($request);
		if ($universities) {
			return response([
				'status'                => 1,
				'msg'                   => 'لیست دانشگاه ها',
				'states'                => $universities[0],
				'cities'                => $universities[1],
				'geographicalLocations' => $universities[2],
				'universities'          => new UniversityCollection($universities[3]),
			]);
		} else {
			return response([
				'status' => 0,
				'msg'    => 'مشکلی به وجود آمده است.لطفا بعدا تلاش کنید',
			]);
		}
	}
	
	// Submit university for user
	public function submitUniversities(Request $request, User $user) {
		$rules = [
			'universities' => ['required', 'array', Rule::exists('universities', 'id')],
		];
		$customMessages = [
			'universities.required' => 'ورود دانشگاه الزامی است',
			'universities.array'    => 'دانشگاه باید به صورت آرایه باشد',
			'universities.exists'   => 'دانشگاه انتخاب شده معتبر نیست',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$submit = $this->universityService->submitUniversities($request, $user);
		if ($submit) {
			return response([
				'status' => 1,
				'msg'    => 'دانشگاه ها برای کاربر با موفقیت ثبت شدند',
			]);
		} else {
			return response([
				'status' => 0,
				'msg'    => 'کاربر یافت نشد',
			]);
		}
	}
	
	// update max university count
	public function updateMaxUniversityCount(Request $request) {
		$rules = [
			'id'    => ['required'],
			'count' => ['required', 'should_be_nums', 'should_be_pos'],
		];
		$customMessages = [
			'count.required'       => 'مقدار را وارد کنید',
			'count.should_be_nums' => 'مقدار معتبر نیست',
			'count.should_be_pos'  => 'مقدار معتبر نیست',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$submit = $this->universityService->updateMaxUniversityCount($request);
		if ($submit) {
			return response([
				'status' => 1,
				'msg'    => 'حداکثر مجاز انتخاب دانشگاه بروز شد',
			]);
		} else {
			return response([
				'status' => 0,
				'msg'    => 'کاربر یافت نشد',
			]);
		}
	}
	
	// get specific user Universities
	public function getUniversities(User $user) {
		$universities = $this->universityService->getUniversities($user);
		if ($universities) {
			return response([
				'status'       => 1,
				'msg'          => 'دانشگاه های کاربر',
				'universities' => new UserUniversityCollection($universities),
				'user'         => new UserResource($user),
			]);
		} else {
			return response([
				'status' => 0,
				'msg'    => 'کاربر یافت نشد',
			]);
		}
	}
	
	// update specific university
	public function updateUniversity(Request $request, UserUniversity $userUniversity) {
		$rules = [
			'field'         => ['required', 'string'],
			'chanceGetting' => ['required', 'integer', Rule::in([1, 2, 3, 4, 5])],
		];
		$customMessages = [
			'field.required' => 'ورود رشته دانشگاهی الزامی است',
			'chanceGetting.required' => 'ورود احتمال اخذ پذیرش الزامی است',
			'chanceGetting.integer'  => 'احتمال اخذ پذیرش باید عدد باشد',
			'chanceGetting.in'       => 'احتمال اخذ پذیرش معتبر نیست',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$university = $this->universityService->updateUniversity($userUniversity, $request);
		if ($university) {
			return response([
				'status' => 1,
				'msg'    => 'دانشگاه با موفقیت ویرایش شد',
			]);
		} else {
			return response([
				'status' => 0,
				'msg'    => 'کاربر یافت نشد',
			]);
		}
	}
	
	// delete specific university
	public function deleteUserUniversity(UserUniversity $userUniversity) {
		$university = $this->universityService->deleteUserUniversity($userUniversity);
		if ($university) {
//			$user = User::find($userUniversity->user_id);
//			$university = University::find($userUniversity->university_id);
			return response([
				'status' => 1,
				'msg'    => 'دانشگاه با موفقیت حذف شد',
//				'sms'    => (new SMS())->sendVerification($user->mobile, 'remove_uni', "name==$user->firstname $user->lastname&university=={$university->title}"),
			]);
		} else {
			return response([
				'status' => 0,
				'msg'    => 'کاربر یافت نشد',
			]);
		}
	}
	
	public function deleteUniversity(UserUniversity $userUniversity) {
		$university = $this->universityService->deleteUniversity($userUniversity);
		if ($university) {
			return response([
				'status' => 1,
				'msg'    => 'دانشگاه با موفقیت حذف شد',
			]);
		} else {
			return response([
				'status' => 0,
				'msg'    => 'کاربر یافت نشد',
			]);
		}
	}
	
	// clone specific university
	public function cloneUniversity(UserUniversity $userUniversity) {
		$university = $this->universityService->cloneUniversity($userUniversity);
		if ($university) {
			return response([
				'status' => 1,
				'msg'    => 'دانشگاه با موفقیت کلون شد',
			]);
		} else {
			return response([
				'status' => 0,
				'msg'    => 'کاربر یافت نشد',
			]);
		}
	}
	
	// delete all universities
	public function deleteAllUniversities(Request $request) {
		$rules = [
			'id' => ['required'],
		];
		$validator = validator()->make($request->all(), $rules);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$university = $this->universityService->deleteAllUniversities($request);
		if ($university) {
			return response([
				'status' => 1,
				'msg'    => 'دانشگاه ها با موفقیت حذف شدند',
			]);
		} else {
			return response([
				'status' => 0,
				'msg'    => 'کاربر یافت نشد',
			]);
		}
	}
	
	// delete all universities
	public function changeUniversityStatus(Request $request) {
		$rules = [
			'id'           => ['required'],
			'universityId' => ['required'],
			'status'       => ['required'],
		];
		$validator = validator()->make($request->all(), $rules);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$university = $this->universityService->changeUniversityStatus($request);
		if ($university) {
			return response([
				'status' => 1,
				'msg'    => 'وضعیت اپلای با موفقیت تغییر کرد',
			]);
		}
		return response([
			'status' => 0,
			'msg'    => 'کاربر یافت نشد',
		]);
	}

    function cloneUserUniversity(Request $request){
        $universities = UserUniversity::where('user_id', $request->id)->get();
        foreach ($universities as $university){
            $uni = new UserUniversity();
            $uni->user_id = $request->userid;
            $uni->university_id = $university->university_id;
            $uni->field = $university->field;
            $uni->chance_getting = $university->chance_getting;
            $uni->description = $university->description;
            $uni->offer = $university->offer;
            $uni->status = 2;
            $uni->deadline = $university->deadline;
            $uni->link = $university->link;
            $uni->level_status = 0;
            $uni->save();
        }
        return response([
            'status' => 1,
            'msg'    => 'ثبت دانشگاه با موفقیت انجام گردید',
        ]);
    }
}
