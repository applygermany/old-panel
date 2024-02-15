<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Services\V1\User\ResumeService;
use App\Http\Resources\User\ResumesResource;

class ResumeController extends Controller {
	protected $resume;
	
	public function __construct(ResumeService $resume) {
		$this->resume = $resume;
	}
	
	public function resumeId($id) {
		$resume = $this->resume->resumeId($id);
		if (!$resume) {
			return response([
				'status'  => 0,
				'msg'     => 'یافت نشد',
				'resumes' => [],
			]);
		}
		return response([
			'status'  => 1,
			'msg'     => 'رزومه',
			'resumes' => new ResumesResource($resume),
		]);
	}
	
	public function resume() {
		$resume = $this->resume->resume();
		$uni = $this->resume->universities();
		return response([
			'status'     => 1,
			'msg'        => 'رزومه',
			'resume'     => new ResumesResource($resume),
			'hasUniversity' => $uni,
		]);
	}
	
	public function resumes() {
		$resumes = $this->resume->resumes();
		return response([
			'status'  => 1,
			'msg'     => 'رزومه',
			'resumes' => ResumesResource::collection($resumes),
		]);
	}
	
	public function updateResume(Request $request) {
		$rules = [
			'id'         => 'required',
			'theme'      => 'nullable|should_be_nums',
			'language'   => 'nullable|max:250|bad_chars',
			'name'       => 'nullable|max:250|bad_chars',
			'family'     => 'nullable|max:250|bad_chars',
			'birthDate'  => 'nullable|max:10',
			'birthPlace' => 'nullable|max:250|bad_chars',
			'phone'      => 'nullable|max:250|bad_chars',
			'email'      => 'nullable|max:250|email',
		];
		$customMessages = [
			'theme.should_be_nums' => 'قالب معتبر نیست',
			'language.max'         => 'زبان حداکثر 250 کاراکتر است',
			'language.bad_chars'   => 'زبان معتبر نیست',
			'name.max'             => 'نام حداکثر 250 کاراکتر است',
			'name.bad_chars'       => 'نام معتبر نیست',
			'family.max'           => 'نام خانوادگی حداکثر 250 کاراکتر است',
			'family.bad_chars'     => 'نام خانوادگی معتبر نیست',
			'birthDate.max'        => 'تاریخ تولد حداکثر 10 کاراکتر است',
			'birthPlace.max'       => 'محل تولد حداکثر 250 کاراکتر است',
			'birthPlace.bad_chars' => 'محل تولد معتبر نیست',
			'phone.max'            => 'شماره تماس حداکثر 250 کاراکتر است',
			'phone.bad_chars'      => 'شماره تماس معتبر نیست',
			'email.max'            => 'ایمیل حداکثر 250 کاراکتر است',
			'email.email'          => 'ایمیل معتبر نیست',
		];
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
			$item = (object)$request->resume;
			if (isset($item->educationRecords)) {
				foreach ($item->educationRecords as $education_record) {
					$education_record = (object)$education_record;
					$education_record->id = $request->id;
					$this->resume->addEducationRecord_parsa($education_record);
				}
			}
			if (isset($item->languages)) {
				foreach ($item->languages as $languages) {
					$languages = (object)$languages;
					$languages->id = $request->id;
					$this->resume->addLanguage_parsa($languages);
				}
			}
			if (isset($item->works)) {
				foreach ($item->works as $works) {
					$works = (object)$works;
					$works->id = $request->id;
					$this->resume->addWork_parsa($works);
				}
			}
			if (isset($item->softwareKnowledges)) {
				foreach ($item->softwareKnowledges as $softwareKnowledges) {
					$softwareKnowledges = (object)$softwareKnowledges;
					$softwareKnowledges->id = $request->id;
					$this->resume->addSoftwareKnowledge_parsa($softwareKnowledges);
				}
			}
			if (isset($item->courses)) {
				foreach ($item->courses as $courses) {
					$courses = (object)$courses;
					$courses->id = $request->id;
					$this->resume->addCourse_parsa($courses);
				}
			}
			if (isset($item->researchs)) {
				foreach ($item->researchs as $researchs) {
					$researchs = (object)$researchs;
					$researchs->id = $request->id;
					$this->resume->addResearch_parsa($researchs);
				}
			}
			if (isset($item->hobbies)) {
				foreach ($item->hobbies as $hobbies) {
					$hobbies = (object)$hobbies;
					$hobbies->id = $request->id;
					$this->resume->addHobby($hobbies);
				}
			}
			$validator = validator()->make($request->all(), $rules, $customMessages);
			if ($validator->fails())
				return response()->json([
					'status' => 422,
					'msg'    => 'خطا در مقادیر ورودی',
					'errors' => $validator->errors(),
				]);
			$resume = $this->resume->updateResume($request);
            if ($resume[0] == 100) {
                return response([
                    'status'         => 1,
                    'msg'            => 'سفارش شما با موفقیت ثبت شد',
                    'transaction_id' => 0,
                    'id'             => $resume[1],
                ]);
            } else {
                return response([
                    'status'         => 1,
                    'msg'            => 'در حال انتقال به صفحه پرداخت ...',
                    'transaction_id' => array_reverse(explode("/", $resume[0]))[0],
                    'id'             => $resume[1],
                ]);
            }
		} else {
			return response([
				'status' => 0,
				'msg'    => 'لطفا ابتدا یک دانشگاه را انتخاب کنید',
			]);
		}
		return response([
			'status' => 0,
			'msg'    => 'خطا در بروز رسانی رزومه',
		]);
	}
	
	public function addHobby(Request $request) {
		$rules = [
			'id'    => 'required',
			'title' => 'nullable|max:250|bad_chars',
		];
		$customMessages = [
			'title.max'       => 'عنوان حداکثر 250 کاراکتر است',
			'title.bad_chars' => 'عنوان معتبر نیست',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$hobby = $this->resume->addHobby($request);
		if ($hobby)
			return response([
				'status' => 1,
				'msg'    => 'تفریح با موفقیت اضافه شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در اضافه کردن تفریحات',
		]);
	}
	
	public function updateResumeInformation(Request $request) {
		$rules = [
			'id' => 'required',
		];
		$customMessages = [
			'theme.should_be_nums' => 'قالب معتبر نیست',
			'language.max'         => 'زبان حداکثر 250 کاراکتر است',
			'language.bad_chars'   => 'زبان معتبر نیست',
			'name.max'             => 'نام حداکثر 250 کاراکتر است',
			'name.bad_chars'       => 'نام معتبر نیست',
			'family.max'           => 'نام خانوادگی حداکثر 250 کاراکتر است',
			'family.bad_chars'     => 'نام خانوادگی معتبر نیست',
			'birthDate.max'        => 'تاریخ تولد حداکثر 10 کاراکتر است',
			'birthPlace.max'       => 'محل تولد حداکثر 250 کاراکتر است',
			'birthPlace.bad_chars' => 'محل تولد معتبر نیست',
			'phone.max'            => 'شماره تماس حداکثر 250 کاراکتر است',
			'phone.bad_chars'      => 'شماره تماس معتبر نیست',
			'email.max'            => 'ایمیل حداکثر 250 کاراکتر است',
			'email.email'          => 'ایمیل معتبر نیست',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$resume = $this->resume->updateResumeInformation($request);
		if ($resume) {
			
			return response([
				'status' => 1,
				'msg'    => 'بروزرسانی انجام شد',
				'id'     => $request->id,
			]);
		}
		return response([
			'status' => 0,
			'msg'    => 'خطا در بروز رسانی رزومه',
		]);
	}
	
	public function uploadImage(Request $request) {
		$rules = [
			'image' => 'required|image|mimes:jpeg,png,jpg|max:10000',
		];
		$customMessages = [
			'image.required' => 'تصویر را انتخاب کنید',
			'image.image'    => 'فایل تصویر معتبر نیست',
			'image.mimes'    => 'پسوند تصویر معتبر نیست',
			'image.max'      => 'حجم تصویر باید کمتر از 10 مگابایت باشد',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails()) {
			session()->flash('error', 'خطا در ورودی ها');
			return redirect()->back()->withErrors($validator)->withInput();
		}
		$this->resume->uploadImage($request);
		return response([
			'status' => 1,
			'msg'    => 'تصویر پرسنلی با موفقیت بروز شد',
		]);
	}
	
	public function editResume(Request $request) {
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
		$resume = $this->resume->editResume($request);
		if ($resume)
			return response([
				'status' => 1,
				'msg'    => 'درخواست با موفقیت ثبت شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در انجام عملیات',
		]);
	}
	
	public function addEducationRecord(Request $request) {
		$rules = [
			'id'             => 'required',
			'grade'          => 'nullable|max:250|bad_chars',
			'fromDateYear'   => 'nullable|max:20',
			'fromDateMonth'  => 'nullable|max:20',
			'toDateYear'     => 'nullable|max:20',
			'toDateMonth'    => 'nullable|max:20',
			'schoolName'     => 'nullable|max:250|bad_chars',
			'field'          => 'nullable|max:250|bad_chars',
			'gradeScore'     => 'integer|max:250|bad_chars',
			'city'           => 'nullable|max:250|bad_chars',
		];
		$customMessages = [
			'grade.max'            => 'مقطع حداکثر 250 کاراکتر است',
			'grade.bad_chars'      => 'مقطع معتبر نیست',
			'fromDateYear.max'     => 'از تاریخ سال حداکثر 20 کاراکتر است',
			'fromDateMonth.max'    => 'از تاریخ ماه حداکثر 20 کاراکتر است',
            'toDateYear.max'       => 'تا تاریخ سال حداکثر 20 کاراکتر است',
            'toDateMonth.max'      => 'تا تاریخ ماه حداکثر 20 کاراکتر است',
			'schoolName.max'       => 'نام مدرسه یا دانشگاه حداکثر 250 کاراکتر است',
			'schoolName.bad_chars' => 'نام مدرسه یا دانشگاه معتبر نیست',
			'field.max'            => 'رشته حداکثر 250 کاراکتر است',
			'field.bad_chars'      => 'رشته معتبر نیست',
			'gradeScore.max'       => 'معدل حداکثر 250 کاراکتر است',
			'gradeScore.bad_chars' => 'معدل معتبر نیست',
			'city.max'             => 'شهر محل تحصیل حداکثر 250 کاراکتر است',
			'city.bad_chars'       => 'شهر محل تحصیل معتبر نیست',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$educationRecord = $this->resume->addEducationRecord($request);
		if ($educationRecord)
			return response([
				'status' => 1,
				'msg'    => 'سوابق تحصیلی با موفقیت اضافه شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در اضافه کردن سوابق تحصیلی',
		]);
	}
	
	public function deleteEducationRecord(Request $request) {
		$rules = [
			'id' => 'required|should_be_nums',
		];
		$validator = validator()->make($request->all(), $rules);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$softwareKnowledge = $this->resume->deleteEducationRecord($request);
		if ($softwareKnowledge)
			return response([
				'status' => 1,
				'msg'    => '   سوابق تحصیلی  با موفقیت حذف شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در حذف کردن  سوابق تحصیلی       ',
		]);
	}
	
	public function addLanguage(Request $request) {
		$rules = [
			'id'                   => 'required',
			'title'                => 'nullable|max:250|bad_chars',
			'fluencyLevel'         => 'nullable|bad_chars',
			'degree'               => 'nullable|max:250|bad_chars',
			'score'                => 'integer|max:250',
			'currentStatus'        => 'max:250',
		];
		$customMessages = [
			'title.max'                      => 'مقطع حداکثر 250 کاراکتر است',
			'title.bad_chars'                => 'مقطع معتبر نیست',
			'fluencyLevel.bad_chars'         => 'میزان تسلط معتبر نیست',
			'degree.max'                     => 'مدرک حداکثر 250 کاراکتر است',
			'degree.bad_chars'               => 'مدرک معتبر نیست',
			'score.max'                      => 'نمره حداکثر 250 کاراکتر است',
			'currentStatus.max'              => 'وضعیا فعلی حداکثر 250 کاراکتر است',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$language = $this->resume->addLanguage($request);
		if ($language)
			return response([
				'status' => 1,
				'msg'    => 'دانش زبانی با موفقیت اضافه شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در اضافه کردن دانش زبانی',
		]);
	}
	
	public function deleteLanguage(Request $request) {
		$rules = [
			'id' => 'required|should_be_nums',
		];
		$validator = validator()->make($request->all(), $rules);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$softwareKnowledge = $this->resume->deleteLanguage($request);
		if ($softwareKnowledge) {
			return response([
				'status' => 1,
				'msg'    => '  دانش زبانی با موفقیت حذف شد',
			]);
		}
		return response([
			'status' => 0,
			'msg'    => 'خطا در حذف کردن دانش زبانی',
		]);
	}
	
	public function addWork(Request $request) {
		$rules = [
			'id'              => 'required',
			'fromDateYear'    => 'nullable|max:20',
			'fromDateMonth'   => 'nullable|max:20',
			'toDateYear'      => 'nullable|max:20',
			'toDateMonth'     => 'nullable|max:20',
			'companyName'     => 'nullable|max:250|bad_chars',
			'position'        => 'nullable|max:250|bad_chars',
			'city'            => 'nullable|max:250|bad_chars',
		];
		$customMessages = [
			'fromDateYear.max'      => 'از تاریخ سال حداکثر 20 کاراکتر است',
			'fromDateMonth.max'     => 'از تاریخ ماه حداکثر 20 کاراکتر است',
			'toDateYear.max'        => 'تا تاریخ سال حداکثر 20 کاراکتر است',
			'toDateMonth.max'       => 'تا تاریخ ماه حداکثر 20 کاراکتر است',
			'companyName.max'       => 'نام شرکت یا دانشگاه حداکثر 250 کاراکتر است',
			'companyName.bad_chars' => 'نام شرکت یا دانشگاه معتبر نیست',
			'position.max'          => 'سمت حداکثر 250 کاراکتر است',
			'position.bad_chars'    => 'سمت معتبر نیست',
			'city.max'              => 'شهر حداکثر 250 کاراکتر است',
			'city.bad_chars'        => 'شهر معتبر نیست',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$work = $this->resume->addWork($request);
		if ($work)
			return response([
				'status' => 1,
				'msg'    => 'سوابق کاری با موفقیت اضافه شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در اضافه کردن سوابق کاری',
		]);
	}
	
	public function deleteWork(Request $request) {
		$rules = [
			'id' => 'required|should_be_nums',
		];
		$validator = validator()->make($request->all(), $rules);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$softwareKnowledge = $this->resume->deleteWork($request);
		if ($softwareKnowledge)
			return response([
				'status' => 1,
				'msg'    => ' سوابق کاری با موفقیت حذف شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در حذف کردن سوابق کاری     ',
		]);
	}
	
	public function addSoftwareKnowledge(Request $request) {
		$rules = [
			'id'           => 'required',
			'title'        => 'nullable|max:250|bad_chars',
			'fluencyLevel' => 'nullable|should_be_nums|minimum_num:0|maximum_num:6',
		];
		$customMessages = [
			'title.max'                   => 'عنوان حداکثر 250 کاراکتر است',
			'title.bad_chars'             => 'عنوان معتبر نیست',
			'fluencyLevel.should_be_nums' => 'میزان تسلط معتبر نیست',
			'fluencyLevel.minimum_num'    => 'میزان تسلط حداقل 0 است',
			'fluencyLevel.maximum_num'    => 'میزان تسلط حداکثر 6 است',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$softwareKnowledge = $this->resume->addSoftwareKnowledge($request);
		if ($softwareKnowledge)
			return response([
				'status' => 1,
				'msg'    => 'دانش نرم افزاری با موفقیت اضافه شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در اضافه کردن دانش نرم افزاری',
		]);
	}
	
	public function deleteSoftwareKnowledge(Request $request) {
		$rules = [
			'id' => 'required|should_be_nums',
		];
		$validator = validator()->make($request->all(), $rules);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$softwareKnowledge = $this->resume->deleteSoftwareKnowledge($request);
		if ($softwareKnowledge)
			return response([
				'status' => 1,
				'msg'    => ' دانش نرم افزاری با موفقیت حذف شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در حذف کردن دانش نرم افزاری   ',
		]);
	}
	
	public function addCourse(Request $request) {
		$rules = [
			'id'        => 'required',
			'title'     => 'nullable|max:250|bad_chars',
			'organizer' => 'nullable|max:250|bad_chars',
			'year'      => 'nullable|max:4|should_be_nums',
		];
		$customMessages = [
			'title.max'           => 'عنوان حداکثر 250 کاراکتر است',
			'title.bad_chars'     => 'عنوان معتبر نیست',
			'organizer.max'       => 'برگزار کننده حداکثر 250 کاراکتر است',
			'organizer.bad_chars' => 'برگزار کننده معتبر نیست',
			'year.max'            => 'سال حداکثر 4 کاراکتر است',
			'year.should_be_nums' => 'سال معتبر نیست',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$course = $this->resume->addCourse($request);
		if ($course)
			return response([
				'status' => 1,
				'msg'    => 'دوره ها و مدارک با موفقیت اضافه شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در اضافه کردن دوره ها و مدارک',
		]);
	}
	
	public function deleteCourse(Request $request) {
		$rules = [
			'id' => 'required|should_be_nums',
		];
		$validator = validator()->make($request->all(), $rules);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$course = $this->resume->deleteCourse($request);
		if ($course)
			return response([
				'status' => 1,
				'msg'    => ' دوره ها و مدارک با موفقیت حذف شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در حذف کردن دوره ها و مدارک',
		]);
	}
	
	public function addResearch(Request $request) {
		$rules = [
			'id'    => 'required',
			'type'  => 'nullable|max:250|bad_chars',
			'title' => 'nullable|max:250|bad_chars',
			'year'  => 'nullable|max:4|should_be_nums',
		];
		$customMessages = [
			'type.max'            => 'نوع پژوهش حداکثر 250 کاراکتر است',
			'type.bad_chars'      => 'نوع پژوهش معتبر نیست',
			'title.max'           => 'عنوان حداکثر 250 کاراکتر است',
			'title.bad_chars'     => 'عنوان معتبر نیست',
			'year.max'            => 'سال حداکثر 4 کاراکتر است',
			'year.should_be_nums' => 'سال معتبر نیست',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$course = $this->resume->addResearch($request);
		if ($course)
			return response([
				'status' => 1,
				'msg'    => 'سوابق پژوهشی با موفقیت اضافه شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در اضافه کردن سوابق پژوهشی',
		]);
	}
	
	public function deleteResearch(Request $request) {
		$rules = [
			'id' => 'required|should_be_nums',
		];
		$validator = validator()->make($request->all(), $rules);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$research = $this->resume->deleteResearch($request);
		if ($research)
			return response([
				'status' => 1,
				'msg'    => 'سوابق پژوهشی با موفقیت حذف شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در حذف کردن سوابق پژوهشی',
		]);
	}
	
	public function deleteHobby(Request $request) {
		$rules = [
			'id' => 'required|should_be_nums',
		];
		$validator = validator()->make($request->all(), $rules);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$hobby = $this->resume->deleteHobby($request);
		if ($hobby)
			return response([
				'status' => 1,
				'msg'    => 'تفریح با موفقیت حذف شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در حذف کردن تفریح',
		]);
	}
	
	public function deletePDF(Request $request) {
		
		$delete = $this->resume->deletePDF($request);
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
	
	public function updateResumeExtra(Request $request) {
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
		$resume = $this->resume->updateResumeExtra($request);
		if ($resume)
			return response([
				'status' => 1,
				'msg'    => 'رزومه موفقیت بروز شد',
			]);
		return response([
			'status' => 0,
			'msg'    => 'خطا در انجام عملیات',
		]);
	}
	
	public function uploadPDF(Request $request) {
		$rules = [
			'id'   => 'required',
			'file' => 'required|mimes:pdf|max:100000',
		];
		$customMessages = [
			'file.required' => 'فایل را انتخاب کنید',
			'file.mimes'    => 'پسوند فایل معتبر نیست',
			'file.max'      => 'حجم فایل باید کمتر از 100 مگابایت باشد',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$this->resume->uploadPDF($request);
		return response([
			'status' => 1,
			'msg'    => 'فایل رزومه با موفقیت آپلود شد',
		]);
	}
}
