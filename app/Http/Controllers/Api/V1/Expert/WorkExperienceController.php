<?php

namespace App\Http\Controllers\Api\V1\Expert;

use App\Http\Controllers\Controller;
use App\Http\Services\V1\Expert\WorkExperienceService;
use App\Models\Acceptance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WorkExperienceController extends Controller
{
    protected $service;

    public function __construct(WorkExperienceService $service)
    {
        $this->service = $service;
    }

    public function getHistoryWorkExperience(Request $request)
    {
        $rules = [
            'name' => 'nullable|bad_chars',
            'category' => 'nullable|should_be_nums',
        ];
        $customMessages = [
            'name.bad_chars' => 'نام وارد شده حاوی کاراکتر های غیر مجاز است',
            'category.should_be_nums' => 'دسته بندی معتبر نیست',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);

        $history = $this->service->getHistory($request);
        if ($history) {
            return response([
                'status' => 1,
                'msg'    => 'تاریخچه فروش موفق',
                'history'=> $history[0],
                'count' =>$history[1]
            ]);
        } else {
            return response([
                'status' => 0,
                'msg'    => 'تاریخچه یافت نشد',
            ]);
        }
    }

    public function changeUploadAccess(Request $request)
    {
        $user = $this->service->changeUploadAccess($request->userId);
        if ($user) {
            $user = User::find($request->userId);
            return response([
                'status'       => 1,
                'uploadAccess' => $user->upload_access === 1,
                'msg'          => 'با موفقیت انجام شد',
            ]);
        } else {
            return response([
                'status' => 0,
                'msg'    => 'کاربر یافت نشد',
            ]);
        }
    }
}
