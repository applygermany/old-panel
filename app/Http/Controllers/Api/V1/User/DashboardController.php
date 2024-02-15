<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Models\Option;
use App\Models\UserDuty;
use App\Models\Votes;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\DashboardAcceptanceResource;
use App\Http\Resources\User\DashboardDutiesResource;
use App\Http\Resources\User\DashboardSupervisorResource;
use App\Http\Resources\User\DashboardTransactionsResource;
use App\Http\Resources\User\DashboardUserResource;
use App\Http\Resources\User\DashboardUsersResource;
use App\Http\Services\V1\User\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $dashboard;

    public function __construct(DashboardService $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function dashboard()
    {

        $dashboard = $this->dashboard->dashboard();

        $userSupport = NULL;
        $userSupervisor = NULL;
        if ($dashboard['userSupport'] != NULL)
            $userSupport = new DashboardSupervisorResource($dashboard['userSupport']);
        if ($dashboard['userSupervisor'] != NULL)
            $userSupervisor = new DashboardSupervisorResource($dashboard['userSupervisor']);
        $acceptance = NULL;
        if ($dashboard['acceptance'] != NULL)
            $acceptance = new DashboardAcceptanceResource($dashboard['acceptance']);
        $inviteUsers = array();
        foreach ($dashboard['users'] as $invite) {
            $inviteUsers[] = new DashboardUsersResource($invite);
        }
        $invitingState = [];
        $invites = auth()->guard("api")->user()->invites()->orderBy('id', 'DESC')->paginate(10);
        foreach ($invites as $invite) {
            if ($invite->invoices()->where("invoice_title", 'receipt')->first()) {
                $invitingState[] = ["user" => $invite, "status" => "تسویه حساب"];
            } elseif ($invite->userUniversities()->where("status", 3)->first()) {
                $invitingState[] = ["user" => $invite, "status" => "اخذ پذیرش"];
            } elseif ($invite->userUniversities()->first()) {
                $invitingState[] = ["user" => $invite, "status" => "در دست اپلای"];
            } elseif ($invite->acceptances()->first()) {
                $invitingState[] = ["user" => $invite, "status" => "درخواست اخذ پذیرش"];
            } else {
                $invitingState[] = ["user" => $invite, "status" => "ثبت نام در پورتال"];
            }
        }
        $votes = [];
        $id = auth()->guard('api')->id();
        $comments = Comment::where('author', '=', $id)->where("status", 1)->where('type', '2')->get();
        foreach ($comments as $comment) {
            $votes[] = [
                'expert_id' => $comment->owner,
                'expertId' => $comment->owner,
            ];
        }
        $vts = Votes::where('user_id', '=', $id)->get();

        foreach ($vts as $vote) {
            $votes[] = [
                'expert_id' => $vote->expert_id,
                'expertId' => $vote->expert_id,
            ];
        }


        return response([
            'status' => 1,
            'msg' => 'داشبورد',
            'user' => new DashboardUserResource($dashboard['user']),
            'acceptance' => $acceptance,
            'balance' => [
                "check" => $dashboard['balance'][0],
                "amount" => $dashboard['balance'][1],
                "ir_amount" => $dashboard['balance'][2],
                "walletBalance" => $dashboard['balance'][3],
            ],
            'userApplyLevelStatus' => $dashboard['userApplyLevelStatus'],
            'user_version' => Option::where('name', 'user_version')->first()->value,
            'userDuties' => DashboardDutiesResource::collection($dashboard['userDuties']),
            'userSupervisorFirstTelSupport' => $dashboard['userSupervisorFirstTelSupport'],
            'userSupervisor' => $userSupervisor,
            'userSupport' => $userSupport,
            'votes' => array_values($votes),
            'transactions' => DashboardTransactionsResource::collection($dashboard['transactions']),
            'users' => $inviteUsers,
            'invites' => $invitingState,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $rules = [
            // 'firstname' => 'max:250|bad_chars',
            // 'lastname' => 'max:250|bad_chars',
            // 'birthDate' => 'max:250',
            // 'codemelli' => 'max:10|should_be_nums',
            // 'firstnameEn' => 'max:250|string',
            // 'lastnameEn' => 'max:250|string'
        ];
        $customMessages = [
            'firstname.required' => 'نام را وارد کنید',
            'firstname.max' => 'نام حداکثر باید 250 کاراکتر باشد',
            'firstname.bad_chars' => 'نام معتبر نیست',
            'lastname.required' => 'نام خانوادگی را وارد کنید',
            'lastname.max' => 'نام خانوادگی حداکثر باید 250 کاراکتر باشد',
            'lastname.bad_chars' => 'نام خانوادگی معتبر نیست',
            'birthDate.required' => 'تاریخ تولد را وارد کنید',
            'birthDate.max' => 'تاریخ تولد حداکثر باید 250 کاراکتر باشد',
            'codemelli.required' => 'کد ملی را وارد کنید',
            'codemelli.max' => 'کد ملی حداکثر باید 250 کاراکتر باشد',
            'codemelli.should_be_nums' => 'کد ملی معتبر نیست',
            'firstnameEn.required' => 'نام انگلیسی را وارد کنید',
            'firstnameEn.max' => 'نام انگلیسی حداکثر باید 250 کاراکتر باشد',
            'firstnameEn.string' => 'نام انگلیسی معتبر نیست',
            'lastnameEn.required' => 'نام خانوادگی انگلیسی را وارد کنید',
            'lastnameEn.max' => 'نام خانوادگی انگلیسی حداکثر باید 250 کاراکتر باشد',
            'lastnameEn.string' => 'نام خانوادگی انگلیسی معتبر نیست',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $updateProfile = $this->dashboard->updateProfile($request);
        if ($updateProfile)
            return response(['status' => 1, 'msg' => 'پروفایل بروزرسانی شد']);
        return response(['status' => 0, 'msg' => 'خطا در بروزرسانی پروفایل']);
    }

    public function uploadImage(Request $request)
    {
        $rules = [
            'image' => 'required|mimes:jpeg,png,jpg|max:5000',
        ];
        $customMessages = [
            'image.required' => 'تصویر را انتخاب کنید',
            'image.mimes' => 'پسوند تصویر معتبر نیست',
            'image.max' => 'حجم تصویر باید کمتر از 5 مگابایت باشد',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $upload = $this->dashboard->uploadImage($request);
        if ($upload)
            return response([
                'status' => 1,
                'msg' => 'تصویر با موفقیت ارسال شد',
            ]);
        return response([
            'status' => 0,
            'msg' => 'خطا در ذخیره تصویر',
        ]);
    }

    public function changeEmailMobile(Request $request)
    {
        $rules = [
            'newEmail' => 'required_without:newMobile|nullable|max:250|email',
            'newMobile' => 'required_without:newEmail|nullable|max:11|should_be_nums',
        ];
        $customMessages = [
            'newEmail.required_without' => 'ایمیل را وارد کنید',
            'newEmail.max' => 'ایمیل حداکثر باید 250 کاراکتر باشد',
            'newEmail.email' => 'ایمیل معتبر نیست',
            'newMobile.required_without' => 'شماره موبایل را وارد کنید',
            'newMobile.max' => 'شماره موبایل حداکثر باید 11 کاراکتر باشد',
            'newMobile.should_be_nums' => 'شماره موبایل معتبر نیست',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $changeEmailMobile = $this->dashboard->changeEmailMobile($request);
        if ($changeEmailMobile == 1)
            return response(['status' => 1, 'msg' => 'کد امنیتی ارسال شد']);
        elseif ($changeEmailMobile == 2)
            return response(['status' => 0, 'msg' => 'ایمیل انتخابی شما توسط شخص دیگری استفاده شده']);
        elseif ($changeEmailMobile == 3)
            return response(['status' => 0, 'msg' => 'موبایل انتخابی شما توسط شخص دیگری استفاده شده']);
        return response(['status' => 0, 'msg' => 'خطا در ارسال کد امنیتی']);
    }

    public function changeEmailMobileVerify(Request $request)
    {
        $rules = [
            'code' => 'required|should_be_nums',
        ];
        $customMessages = [
            'code.required' => 'کد را وارد کنید',
            'code.should_be_nums' => 'کد معتبر نیست',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $changeEmailMobile = $this->dashboard->changeEmailMobileVerify($request);
        if ($changeEmailMobile == 1)
            return response(['status' => 1, 'msg' => 'تغییرات اعمال شد']);
        elseif ($changeEmailMobile == 2)
            return response(['status' => 0, 'msg' => 'کد اشتباه است']);
        elseif ($changeEmailMobile == 3)
            return response(['status' => 0, 'msg' => 'موبایل تکراری است']);
        elseif ($changeEmailMobile == 4)
            return response(['status' => 0, 'msg' => 'ایمیل تکراری است']);
        return response(['status' => 0, 'msg' => 'خطا در بروزرسانی اطلاعات']);
    }

    public function changeEmailMobileResendCode()
    {
        $changeEmailMobile = $this->dashboard->changeEmailMobileResendCode();
        if ($changeEmailMobile == 1)
            return response(['status' => 1, 'msg' => 'کد امنیتی ارسال شد']);
        elseif ($changeEmailMobile == 2)
            return response(['status' => 0, 'msg' => 'ایمیل انتخابی شما توسط شخص دیگری استفاده شده']);
        elseif ($changeEmailMobile == 3)
            return response(['status' => 0, 'msg' => 'موبایل انتخابی شما توسط شخص دیگری استفاده شده']);
        return response(['status' => 0, 'msg' => 'خطا در ارسال کد امنیتی']);
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'oldPassword' => 'required',
            'newPassword' => 'required|confirmed',
        ];
        $customMessages = [
            'oldPassword.required' => 'گذرواژه فعلی را وارد کنید',
            'newPassword.required' => 'گذرواژه جدید را وارد کنید',
            'newPassword.confirmed' => 'گذرواژه جدید با تایپ مجدد آن خوانایی ندارد',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);
        $changeEmailMobile = $this->dashboard->updatePassword($request);
        if ($changeEmailMobile == 1)
            return response(['status' => 1, 'msg' => 'تغییرات اعمال شد']);
        elseif ($changeEmailMobile == 2)
            return response(['status' => 0, 'msg' => 'گذرواژه فعلی اشتباه است']);
        return response(['status' => 0, 'msg' => 'خطا در بروزرسانی اطلاعات']);
    }

    public function changeDarkMode()
    {
        $this->dashboard->changeDarkMode();
        return response(['status' => 1, 'msg' => 'اعمال شد']);
    }

    public function doneDuty(Request $request)
    {
        $duty = UserDuty::find($request->id);
        $duty->status = 3;
        if ($duty->save()) {

            if(isset($request->timestamp)) {
                $client = new \GuzzleHttp\Client();
                try {
                    $res = $client->request("POST", "https://chat.applygermany.net/deletenotification", [
                        'json' => [
                            'userid' => $duty->user_id,
                            'timestamp' => $request->timestamp,
                        ],
                        "headers" => [
                            "authentication" => "MVKI2w5oCRGKSoStcUih368ZT5VrktKGTMDj9YotxWPpcgS6FyEoWlUSMgilveXWlVJcrkwa9TznyP52e1WnpQf0jqshOSp66mhyYBmbr6tVklcDwU4y2XyuQgR",
                        ],
                    ]);
                } catch (\Exception $e) {
                    //echo $e->getMessage();
                }
            }
        }
    }
}
