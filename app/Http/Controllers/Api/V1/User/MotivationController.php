<?php

namespace App\Http\Controllers\Api\V1\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\MotivationsResource;
use App\Http\Services\V1\User\MotivationService;
use App\Providers\Notification;
use App\Mail\MailVerificationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class MotivationController extends Controller {
	protected $motivation;
	
	public function __construct(MotivationService $motivation) {
		$this->motivation = $motivation;
	}
	
	public function motivations() {
		$motivations = $this->motivation->motivations();
		return response([
			'status'      => 1,
			'msg'         => 'لیست انگیزه نامه ها',
			'motivations' => MotivationsResource::collection($motivations),
		]);
	}
	
	public function motivation($id) {
		$motivation = $this->motivation->motivation($id);
		if (!$motivation) {
			
			return response([
				'status'     => 0,
				'msg'        => 'یافت نشد',
				'motivation' => [],
			]);
		}
		return response([
			'status'     => 1,
			'msg'        => 'انگیزه نامه',
			'motivation' => new MotivationsResource($motivation),
		]);
	}
	
	
	public function saveMotivation(Request $request) {
		$rules = [
			'motivation.name'       => 'required|max:250|bad_chars',
			'motivation.family'     => 'required|max:250|bad_chars',
			'motivation.email'      => 'required|max:250|email',
			'motivation.phone'      => 'required|max:250',
			'motivation.birthDate'  => 'required|max:10',
			'motivation.birthPlace' => 'required|max:255',
			'motivation.address'    => 'required|max:255',
			'motivation.about'      => 'required|max:7000',
			'motivation.resume'      => 'required|max:7000',
		];
		$customMessages = [
			'motivation.name.required'       => 'ورود نام الزامی است',
			'motivation.name.max'            => 'نام حداکثر باید 250 کاراکتر باشد',
			'motivation.name.bad_chars'      => 'نام معتبر نیست',
			'motivation.family.required'     => 'ورود نام خانوادگی الزامی است',
			'motivation.family.max'          => 'نام خانوادگی حداکثر باید 250 کاراکتر باشد',
			'motivation.family.bad_chars'    => 'نام خانوادگی معتبر نیست',
            'motivation.email.required'      => 'ورود ایمیل الزامی است',
            'motivation.email.max'           => 'ایمیل حداکثر باید 250 کاراکتر باشد',
            'motivation.email.email'         => 'ایمیل معتبر نمی باشد',
            'motivation.phone.required'      => 'ورود شماره تماس الزامی است',
            'motivation.phone.max'           => 'شماره تماس حداکثر باید 250 کاراکتر باشد',
            'motivation.birthDate.required'  => 'ورود تاریخ تولد الزامی است',
            'motivation.birthDate.max'       => 'تاریخ تولد حداکثر باید 10 کاراکتر باشد',
            'motivation.birthPlace.required' => 'ورود مجل تولد الزامی است',
            'motivation.birthPlace.max'      => 'محل تولد حداکثر باید 10 کاراکتر باشد',
            'motivation.address.required'    => 'ورود آدرس الزامی است',
            'motivation.address.max'         => 'آدرس حداکثر باید 300 کاراکتر باشد',
            'motivation.about.required'      => 'ورود درباره خودتان الزامی است',
            'motivation.about.max'           => 'درباره خودتان حداکثر باید 7000 کاراکتر باشد',
            'motivation.resume.required'     => 'ورود رزومه الزامی است',
            'motivation.resume.max'          => 'رزومه حداکثر باید 7000 کاراکتر باشد',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		if (strpos($request->motivation['phone'], 'e') !== false) {
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		}
		$select = "select `nag_user_universities`.`user_id`,
`nag_user_universities`.`id`,
`nag_user_universities`.`university_id`,
`nag_user_universities`.`field`,
`nag_user_universities`.chance_getting,
`nag_user_universities`.`description`,
`nag_user_universities`.`offer`,
`nag_user_universities`.`link`,
`nag_user_universities`.`status`,
`nag_user_universities`.`level_status`,
`nag_universities`.`title`,
`nag_universities`.`city`,
`nag_universities`.`state`,
`nag_universities`.`geographical_location`,
`nag_universities`.`city_crowd`,
`nag_universities`.`cost_living`,
`nag_universities`.`updated_at`
from `nag_user_universities`";
		$select .= " inner join `nag_users` on `nag_user_universities`.`user_id` = `nag_users`.`id`";
		$select .= " inner join `nag_universities` on `nag_user_universities`.`university_id` = `nag_universities`.`id`";
		$select .= " where `nag_user_universities`.`user_id` = " . auth()->guard('api')->id();
		$select .= " and `nag_user_universities`.`status` = 1";
		$select .= ' order by nag_user_universities.id desc';
		$universities = DB::select($select);
		if (count($universities) > 0) {
			$motivation = $this->motivation->saveMotivation($request);
			if ($motivation) {
				if ($motivation[0] == 100) {
					return response([
						'status'         => 1,
						'msg'            => 'سفارش شما با موفقیت ثبت شد',
						'transaction_id' => 0,
						'id'             => $motivation[1],
					]);
				}else {
                    return response([
                        'status'         => 1,
                        'msg'            => 'در حال انتقال به صفحه پرداخت ...',
                        'transaction_id' => array_reverse(explode("/", $motivation[0]))[0],
                        'id'             => $motivation[1],
                    ]);
                }
			}
		} else {
			return response([
				'status' => 0,
				'msg'    => 'لطفا ابتدا یک دانشگاه را انتخاب کنید',
			]);
		}
		return response([
			'status' => 0,
			'msg'    => 'خطا در انجام عملیات',
		]);
	}
	
	public function updateMotivationExtra(Request $request) {
		$rules = [
			'id'        => 'required|should_be_nums',
			'extraText' => 'required',
		];
		$customMessages = [
			'id.required'        => 'ورود تاریخ الزامی است',
			'id.should_be_nums'  => 'فرمت تاریخ اشتباه است',
			'extraText.required' => 'ورود توضیحات الزامی است',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$motivation = $this->motivation->updateMotivationExtra($request);
		if ($motivation)
			return response([
				'status' => 1,
				'msg'    => 'انگیزه نامه با موفقیت بروز شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در انجام عملیات',
		]);
	}
	
	public function editMotivation(Request $request) {
		$rules = [
			'id'   => 'required|should_be_nums',
			'file' => 'nullable|mimes:pdf|max:100000',
		];
		$customMessages = [
			'id.required'       => 'ورود تاریخ الزامی است',
			'id.should_be_nums' => 'فرمت تاریخ اشتباه است',
			'file.mimes'        => 'فایل معتبر نیست',
			'file.max'          => 'فایل باید کمتر از 100 مگابایت باشد',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$motivation = $this->motivation->editMotivation($request);
		if ($motivation)
			return response([
				'status' => 1,
				'msg'    => 'درخواست با موفقیت ثبت شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در انجام عملیات',
		]);
	}
	
	public function updateMotivation(Request $request) {
		$motivation = $this->motivation->updateMotivation($request);
		if ($motivation)
			return response([
				'status' => 1,
				'msg'    => 'انگیزه نامه با موفقیت ویرایش شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در انجام عملیات',
		]);
	}
	
	public function uploadResume(Request $request) {
		
		$rules = [
			'id'   => 'required|should_be_nums',
			'file' => 'nullable|mimes:pdf|max:100000',
		];
		$customMessages = [
			'id.required'       => 'ورود آیدی الزامی است',
			'id.should_be_nums' => 'فرمت آیدی اشتباه است',
			'file.mimes'        => 'فایل معتبر نیست',
			'file.max'          => 'فایل باید کمتر از 100 مگابایت باشد',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$upload = $this->motivation->uploadResume($request);
		if ($upload) {
			return response([
				'status' => 1,
				'msg'    => 'فایل رزومه با موفقیت ذخیره شد',
			]);
		}
		return response([
			'status' => 0,
			'msg'    => 'خطا در انجام عملیات',
		]);
	}
	
	public function newMotivation() {
		$uni = $this->motivation->universities();
		$motivation = $this->motivation->newMotivation();
		return response([
			'status'        => 1,
			'msg'           => 'انگیزه نامه',
			'motivation'    => new MotivationsResource($motivation),
			'hasUniversity' => !($uni == 0),
		]);
	}
	
	public function deletePDF(Request $request) {
		
		$delete = $this->motivation->deletePDF($request);
		if ($delete)
			return response([
				'status' => 1,
				'msg'    => 'فایل با موفقیت حذف شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در حذف کردن فایل',
		]);
	}
	
}
