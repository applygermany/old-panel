<?php

namespace App\Http\Controllers\Api\V1\Expert;

use App\Http\Controllers\Controller;
use App\Http\Resources\Expert\UserDocumentCollection;
use App\Http\Resources\Expert\UserResource;
use App\Http\Resources\User\UniversitiesResource;
use App\Http\Services\V1\Expert\DashboardService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $dashboard;

    public function __construct(DashboardService $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function downloadFiles($type,$id){

        $expert=auth()->guard('api')->id();

        if (is_file(public_path('uploads/'.$type.'/' . $id . '.pdf'))) {
            $file = public_path('uploads/'.$type.'/' . $id . '.pdf');
            $fileName=$id . '.pdf';
        } elseif (is_file(public_path('uploads/'.$type.'/' . $id . '.jpg'))) {
            $file = public_path('uploads/'.$type.'/' . $id . '.jpg');
            $fileName=$id . '.jpg';
        } elseif (is_file(public_path('uploads/'.$type.'/' . $id . '.rar'))) {
            $file = public_path('uploads/'.$type.'/' . $id . '.rar');
            $fileName=$id . '.rar';
        } elseif (is_file(public_path('uploads/'.$type.'/' . $id . '.zip'))) {
            $file = public_path('uploads/'.$type.'/' . $id . '.zip');
            $fileName=$id . '.zip';
        }

        return response()->download($file, $fileName);
    }

    public function uploadImage(Request $request)
    {
        $rules = [
            'image' => 'required|mimes:jpeg,png,jpg|max:5000'
        ];

        $customMessages = [
            'image.required' => 'تصویر را انتخاب کنید',
            'image.mimes' => 'پسوند تصویر معتبر نیست',
            'image.max' => 'حجم تصویر باید کمتر از 5 مگابایت باشد'
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
                'msg' => 'تصویر با موفقیت ارسال شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'خطا در ذخیره تصویر'
        ]);
    }

    public function changeEmailMobile(Request $request)
    {
        $rules = [
            'newEmail' => 'required_without:newMobile|nullable|max:250|email',
            'newMobile' => 'required_without:newEmail|nullable|max:11|should_be_nums'
        ];

        $customMessages = [
            'newEmail.required_without' => 'ایمیل را وارد کنید',
            'newEmail.max' => 'ایمیل حداکثر باید 250 کاراکتر باشد',
            'newEmail.email' => 'ایمیل معتبر نیست',

            'newMobile.required_without' => 'شماره موبایل را وارد کنید',
            'newMobile.max' => 'شماره موبایل حداکثر باید 11 کاراکتر باشد',
            'newMobile.should_be_nums' => 'شماره موبایل معتبر نیست'
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
        else if ($changeEmailMobile == 2)
            return response(['status' => 0, 'msg' => 'ایمیل انتخابی شما توسط شخص دیگری استفاده شده']);
        else if ($changeEmailMobile == 3)
            return response(['status' => 0, 'msg' => 'موبایل انتخابی شما توسط شخص دیگری استفاده شده']);
        return response(['status' => 0, 'msg' => 'خطا در ارسال کد امنیتی']);
    }

    public function changeEmailMobileVerify(Request $request)
    {
        $rules = [
            'code' => 'required|should_be_nums'
        ];

        $customMessages = [
            'code.required' => 'کد را وارد کنید',
            'code.should_be_nums' => 'کد معتبر نیست'
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
        else if ($changeEmailMobile == 2)
            return response(['status' => 0, 'msg' => 'کد اشتباه است']);
        else if ($changeEmailMobile == 3)
            return response(['status' => 0, 'msg' => 'موبایل تکراری است']);
        else if ($changeEmailMobile == 4)
            return response(['status' => 0, 'msg' => 'ایمیل تکراری است']);
        return response(['status' => 0, 'msg' => 'خطا در بروزرسانی اطلاعات']);
    }

    public function changeEmailMobileResendCode()
    {
        $changeEmailMobile = $this->dashboard->changeEmailMobileResendCode();
        if ($changeEmailMobile == 1)
            return response(['status' => 1, 'msg' => 'کد امنیتی ارسال شد']);
        else if ($changeEmailMobile == 2)
            return response(['status' => 0, 'msg' => 'ایمیل انتخابی شما توسط شخص دیگری استفاده شده']);
        else if ($changeEmailMobile == 3)
            return response(['status' => 0, 'msg' => 'موبایل انتخابی شما توسط شخص دیگری استفاده شده']);
        return response(['status' => 0, 'msg' => 'خطا در ارسال کد امنیتی']);
    }

    public function updatePassword(Request $request)
    {
        $rules = [
            'oldPassword' => 'required',
            'newPassword' => 'required|confirmed'
        ];

        $customMessages = [
            'oldPassword.required' => 'گذرواژه فعلی را وارد کنید',

            'newPassword.required' => 'گذرواژه جدید را وارد کنید',
            'newPassword.confirmed' => 'گذرواژه جدید با تایپ مجدد آن خوانایی ندارد'
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
        else if ($changeEmailMobile == 2)
            return response(['status' => 0, 'msg' => 'گذرواژه فعلی اشتباه است']);
        return response(['status' => 0, 'msg' => 'خطا در بروزرسانی اطلاعات']);
    }

    public function changeDarkMode()
    {
        $this->dashboard->changeDarkMode();
        return response(['status' => 1, 'msg' => 'اعمال شد']);
    }

    public function getDocument(User $user)
    {
        return response([
            'status' => 1,
            'msg' => 'مدارک کاربر',
            'documents' => new UserDocumentCollection($user->uploads),
            'user' => new UserResource($user)
        ]);
    }

    public function getApply(User $user)
    {
        $apply = $this->dashboard->getApply($user);
        return response([
            'status' => 1,
            'msg' => 'لیست دانشگاه ها',
            'universities' => UniversitiesResource::collection($apply),
            'user' => new UserResource($user),
        ]);
    }
//    function usersList()
//    {
//        $users = User::where('level', 1)->whereIn('type', [2, 3])->get();
//        return response([
//            'status' => 1,
//            'msg' => 'لیست کاربران',
//            'users' => $users,
//        ]);
//    }
    function usersList($uni = '')
    {
        if($uni==1){
            $users=DB::select('select * from nag_users where level=1 and type=2');
        }else{
            $users=DB::select('select * from nag_users where level=1 and (type=2 or type=3)');
        }
        return response([
            'status' => 1,
            'msg' => 'لیست کاربران',
            'users' => $users,
        ]);
    }
}
