<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Expert\TelSupportController;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\NextTelSupportResource;
use App\Http\Services\V1\Expert\TelSupportService;
use App\Http\Services\V1\LoginService;
use App\Models\User;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use Illuminate\Http\Request;

class LoginController extends Controller {
	protected $login;
	
	
	public function __construct(LoginService $login) {
		$this->login = $login;
	}
	
	// sign in to account
	public function signIn(Request $request) {
		$rules = [
			'mobile'   => 'required',
			'password' => 'required',
		];
		$customMessages = [
			'mobile.required'   => 'ورود ایمیل/موبایل الزامی است',
			'password.required' => 'ورود گذرواژه الزامی است',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$signIn = $this->login->signIn($request);
		if ($signIn == 2)
			return response()->json([
				'status' => 0,
				'msg'    => 'گذرواژه اشتباه است',
				'token'  => NULL,
			]);
		elseif ($signIn == 0)
			return response()->json([
				'status' => 0,
				'msg'    => 'کاربری با این مشخصات یافت نشد',
				'token'  => NULL,
			]);
		elseif ($signIn == 3)
			return response()->json([
				'status' => 0,
				'msg'    => 'اطلاعات اشتباه است',
				'token'  => NULL,
			]);
		return response()->json([
			'status' => 1,
			'msg'    => 'شما با موفقیت وارد حساب خود شدید',
			'level'  => (int)$signIn['level'],
			'token'  => $signIn['token'],
		]);
	}
	
	// sign in to account
	public function signUp(Request $request) {
		$rules = [
			'firstname'     => 'required|min:2|max:250|bad_chars',
			'lastname'      => 'required|min:2|max:250|bad_chars',
			//'acquaintedWay' => 'nullable|max:250|bad_chars',
			'mobile'        => 'required|max:15',
			'email'         => 'required|max:250|email',
		];
		$customMessages = [
			'firstname.required'      => 'ورود نام الزامی است',
			'firstname.min'           => 'نام حداقل باید 2 حرف باشد',
			'firstname.max'           => 'نام حداکثر باید 250 حرف باشد',
			'firstname.bad_chars'     => 'نام حاوی کاراکتر های غیر مجاز است',
			'lastname.required'       => 'ورود نام خانوادگی الزامی است',
			'lastname.min'            => 'نام خانوادگی حداقل باید 2 حرف باشد',
			'lastname.max'            => 'نام خانوادگی حداکثر باید 250 حرف باشد',
			'lastname.bad_chars'      => 'نام خانوادگی حاوی کاراکتر های غیر مجاز است',
//			'acquaintedWay.max'       => 'نحوه آشنایی حداکثر باید 250 حرف باشد',
//			'acquaintedWay.bad_chars' => 'نحوه آشنایی حاوی کاراکتر های غیر مجاز است',
//			'mobile.required'         => 'ورود موبایل الزامی است',
			'mobile.max'              => 'موبایل حداکثر 10 رقم است',
            'email.required'          => 'ورود ایمیل الزامی است',
            'email.max'               => 'ایمیل حداکثر 250 رقم است',
            'email.email'             => 'ایمیل معتبر نمی باشد',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$signUp = $this->login->signUp($request);
		if ($signUp == 1)
			return response()->json([
				'status' => 1,
				'msg'    => 'مشخصات شما ثبت و ثبت نام شما با موفقیت انجام شد',
			]);
		elseif ($signUp == 2)
			return response()->json([
				'status' => 0,
				'msg'    => 'ایمیل انتخابی قبلا ثبت شده است',
			]);
        elseif ($signUp == 4)
            return response()->json([
                'status' => 0,
                'msg'    => 'موبایل انتخابی قبلا ثبت شده است',
            ]);
		elseif ($signUp == 3)
			return response()->json([
				'status' => 0,
				'msg'    => 'موبایل معتبر نیست',
			]);
		return response()->json([
			'status' => 0,
			'msg'    => 'خطا در ثبت اطلاعات',
		]);
	}
	
	// resend code to email or phone
	public function resendCode(Request $request) {
		$rules = [
			'mobile' => 'required',
		];
		$customMessages = [
			'mobile.required' => 'ورود ایمیل/موبایل الزامی است',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$sendCode = $this->login->resendCode($request);
		if ($sendCode) {
			if ($sendCode == 1) {
				return response([
					'status' => 1,
					'msg'    => 'کد تایید به ایمیل شما ارسال شد',
				]);
			} elseif ($sendCode == 2) {
				return response([
					'status' => 1,
					'msg'    => 'کد تایید به شماره موبایل شما ارسال شد',
				]);
			} elseif ($sendCode == 3) {
				return response([
					'status' => 0,
					'msg'    => 'موبایل معتبر نیست',
				]);
			}
		}
		return response([
			'status' => 0,
			'msg'    => 'کاربر یافت نشد',
		]);
	}
	
	// verify user
	public function verify(Request $request) {
		$rules = [
			'mobile' => 'required',
			'code'   => 'required',
		];
		$customMessages = [
			'mobile.required' => 'ورود ایمیل/موبایل الزامی است',
			'code.required'   => 'لطفا کد امنیتی را وارد کنید',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$verifyUser = $this->login->verify($request);
		if ($verifyUser == 1) {
			return response([
				'status' => 1,
				'msg'    => 'کد امنیتی صحیح است',
			]);
		} elseif ($verifyUser == 2) {
			return response([
				'status' => 0,
				'msg'    => 'کد امنیتی اشتباه است',
			]);
		} elseif ($verifyUser == 3) {
			return response([
				'status' => 0,
				'msg'    => 'موبایل معتبر نیست',
			]);
		}
		return response([
			'status' => 0,
			'msg'    => 'خطا در انجام عملیات',
		]);
	}
	
	// verify user
	public function completeSignUp(Request $request) {
		$rules = [
			'mobile'   => 'required',
			'password' => 'required|confirmed',
            'acquaintedWay' => 'nullable|max:250|bad_chars',
		];
		$customMessages = [
			'mobile.required'    => 'ورود ایمیل/موبایل الزامی است',
			'password.required'  => 'گذرواژه را وارد کنید',
			'password.confirmed' => 'گذرواژه با تایپ مجدد آن خوانایی ندارد',
            'acquaintedWay.max'       => 'نحوه آشنایی حداکثر باید 250 حرف باشد',
			'acquaintedWay.bad_chars' => 'نحوه آشنایی حاوی کاراکتر های غیر مجاز است',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$completeSignUp = $this->login->completeSignUp($request);
		if ($completeSignUp) {
			if ($completeSignUp == 2) {
				return response([
					'status' => 0,
					'msg'    => 'شما قبلا گذرواژه خود را ثبت کرده اید !',
				]);
			} elseif ($completeSignUp == 3) {
				return response([
					'status' => 0,
					'msg'    => 'موبایل معتبر نیست',
				]);
			}
			return response([
				'status' => 1,
				'msg'    => 'اطلاعات با موفقیت ثبت شد',
				'tiken'  => $completeSignUp,
			]);
		}
		return response([
			'status' => 0,
			'msg'    => 'خطا در انجام عملیات',
		]);
	}
	
	// get logged-in user
	public function getUser(Request $request) {
		$user = $this->login->getUser();
		if ($user) {
			
			$date = NULL;
			$dateG = NULL;
			if ($user->birth_date != NULL) {
				$date = MyHelpers::dateToJalali($user->birth_date);
				$dateG = JDF::jalali_to_gregorian_($date, "/");
			}
            if ($user->acceptances->first() != NULL) {
                $acceptanceDate = MyHelpers::dateToJalali($user->acceptances->first()->created_at);
            }
			return response()->json([
				'status' => 1,
				'msg'    => 'مشخصات کاربر',
				'user'   => [
					'id'                 => $user->id,
					'firstname'          => $user->firstname,
					'lastname'           => $user->lastname,
					'firstnameEn'        => $user->firstname_en,
					'lastnameEn'         => $user->lastname_en,
					'supervisor_id'      => $user->supervisor->supervisor_id ?? 0,
					'codemelli'          => $user->codemelli,
					'birthDate'          => $date,
					'birthDateGregorian' => $dateG,
					'email'              => $user->email,
					'mobile'             => $user->mobile,
					'type'               => (int)$user->type,
					'level'              => (int)$user->level,
					'darkmode'           => (int)$user->darkmode,
					'tel_support_flag'   => (int)$user->tel_support_flag,
					'acceptanceDate'     => $acceptanceDate,
					'telSupportAccess'   => $user->admin_permissions->sup_tel === 1 ? true : false,
					'image'              => route('imageUser', [
						'id' => $user->id,
						'ua' => strtotime($user->updated_at),
					]),
				],
			]);
		} else
			return response()->json([
				'status' => 0,
				'msg'    => 'شما وارد حساب کاربری خود نشده اید',
				'user'   => [],
			]);
	}
	
	// send recovery code to email or phone
	public function sendRecoveryCode(Request $request) {
		$rules = [
			'mobileRecovery' => 'required',
		];
		$customMessages = [
			'mobileRecovery.required' => 'ورود ایمیل/موبایل الزامی است',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$sendCode = $this->login->sendRecoveryCode($request);
		if ($sendCode) {
			if ($sendCode == 1) {
				return response([
					'status' => 1,
					'msg'    => 'کد بازیابی به ایمیل شما ارسال شد',
//					'msg'    => 'لطفا رمز عبور جدید خود را انتخاب کنید',
				]);
			} elseif ($sendCode == 2) {
				return response([
					'status' => 1,
					'msg'    => 'کد بازیابی به شماره موبایل شما ارسال شد',
				]);
			} elseif ($sendCode == 3) {
				return response([
					'status' => 0,
					'msg'    => 'موبایل معتبر نیست',
				]);
			}
		}
		return response([
			'status' => 0,
			'msg'    => 'کاربر یافت نشد',
		]);
	}
	
	// verify recovery code
	public function verifyRecoveryCode(Request $request) {
		$rules = [
			'mobile' => 'required',
			'code'   => 'required',
		];
		$customMessages = [
			'mobile.required' => 'ورود ایمیل/موبایل الزامی است',
			'code.required'   => 'لطفا کد امنیتی را وارد کنید',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$verifyRecoveryCode = $this->login->verifyRecoveryCode($request);
		if ($verifyRecoveryCode == 1) {
			return response([
				'status' => 1,
				'msg'    => 'کد امنیتی صحیح است',
			]);
		} elseif ($verifyRecoveryCode == 2) {
			return response([
				'status' => 0,
				'msg'    => 'کد امنیتی اشتباه است',
			]);
		} elseif ($verifyRecoveryCode == 3) {
			return response([
				'status' => 0,
				'msg'    => 'موبایل معتبر نیست',
			]);
		}
		return response([
			'status' => 0,
			'msg'    => 'خطا در انجام عملیات',
		]);
	}
	
	// recover password
	public function recoverPassword(Request $request) {
		$rules = [
			'mobile'   => 'required',
			'code'     => 'required',
			'password' => 'required|confirmed',
		];
		$customMessages = [
			'mobile.required'    => 'ورود ایمیل/موبایل الزامی است',
			'code.required'      => 'لطفا کد امنیتی را وارد کنید',
			'password.required'  => 'گذرواژه را وارد کنید',
			'password.confirmed' => 'گذرواژه با تایپ مجدد آن خوانایی ندارد',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails())
			return response()->json([
				'status' => 422,
				'msg'    => 'خطا در مقادیر ورودی',
				'errors' => $validator->errors(),
			]);
		$recoverPassword = $this->login->recoverPassword($request);
		if ($recoverPassword == 1) {
			return response([
				'status' => 1,
				'msg'    => 'گذرواژه با موفقیت تغییر کرد',
			]);
		} elseif ($recoverPassword == 2) {
			return response([
				'status' => 0,
				'msg'    => 'کد امنیتی اشتباه است',
			]);
		} elseif ($recoverPassword == 3) {
			return response([
				'status' => 0,
				'msg'    => 'موبایل معتبر نیست',
			]);
		}
		return response([
			'status' => 0,
			'msg'    => 'خطا در انجام عملیات',
		]);
	}
	
	// sign out from account
	public function signOut() {
		$logout = $this->login->signOut();
		if ($logout)
			return response()->json([
				'status' => 1,
				'msg'    => 'شما با موفقیت از حساب خود خارج شدید',
			]);
		else
			return response()->json([
				'status' => 0,
				'msg'    => 'شما وارد حساب کاربری خود نشده اید',
			]);
	}
}

