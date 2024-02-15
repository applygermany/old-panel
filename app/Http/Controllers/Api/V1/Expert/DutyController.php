<?php

namespace App\Http\Controllers\Api\V1\Expert;

use App\Http\Controllers\Controller;
use App\Http\Resources\Expert\DutyCollection;
use App\Http\Resources\Expert\UserResource;
use App\Http\Services\V1\Expert\DutyService;
use App\Models\Category;
use App\Models\User;
use App\Models\UserDuty;
use App\Providers\SMS;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DutyController extends Controller
{
    protected $dutyService;

    public function __construct(DutyService $dutyService)
    {
        $this->dutyService = $dutyService;
    }

    // save new duty for user
    public function saveDuty(Request $request, $user)
    {

        $rules = [
            'title' => 'required|string',

            'text' => 'max:255',
            'deadline' => 'required|date'
        ];

        $customMessages = [
            'title.required' => 'ورود عنوان وظیفه الزامی است',

            'text.max' => 'ورودی نهایتا 255 حرف باشد',

            'deadline.required' => 'ورود تاریخ پایان الزامی است',
            'deadline.date' => 'فرمت تاریخ اشتباه است',
        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if (time() > strtotime($request->deadline)) {
            return response([
                'status' => 0,
                'msg' => 'تاریخ ددلاین معتبر نیست'
            ]);
        }

        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);

        $save = $this->dutyService->saveDuty($request, $user);

        if ($save) {

            return response([
                'status' => 1,
                'msg' => 'وظیفه با موفقیت ثبت شد'
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد'
            ]);
        }
    }

    public function saveDutyAll(Request $request, $user)
    {
        $save = $this->dutyService->saveDutyAll($request, $user);
        if ($save) {
            return response([
                'status' => 1,
                'msg' => 'وظیفه با موفقیت ثبت شد'
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد'
            ]);
        }
    }

    // update duty for user
    public function updateDuty(Request $request, UserDuty $userDuty)
    {
        $rules = [
            'title' => 'required|string',
            'text' => 'max:255',
            'deadline' => 'required|date'
        ];


        $customMessages = [
            'title.required' => 'ورود عنوان وظیفه الزامی است',

            'text.max' => 'ورودی نهایتا 255 حرف باشد',

            'deadline.required' => 'ورود تاریخ پایان الزامی است',
            'deadline.date' => 'فرمت تاریخ اشتباه است',
        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);

        $update = $this->dutyService->updateDuty($request, $userDuty);

        if ($update) {
            return response([
                'status' => 1,
                'msg' => 'وظیفه با موفقیت ویرایش شد'
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد'
            ]);
        }
    }

    // update status duty for user
    public function updateStatusDuty(Request $request, UserDuty $userDuty)
    {
        $rules = [
            'status' => ['required', Rule::in([1, 2, 3])],
        ];

        $customMessages = [
            'status.required' => 'ورود وضعیت وظیفه الزامی است',
            'status.in' => 'فرمت وضعیت وظیفه اشتباه است',
        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg' => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);

        $update = $this->dutyService->updateStatusDuty($request, $userDuty);

        if ($update) {
            return response([
                'status' => 1,
                'msg' => 'وضعیت وظیفه با موفقیت ویرایش شد'
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد'
            ]);
        }
    }

    // delete duty for user
    public function deleteDuty(UserDuty $userDuty)
    {
        $delete = $this->dutyService->deleteDuty($userDuty);

        if ($delete) {
            return response([
                'status' => 1,
                'msg' => 'وظیفه با موفقیت حذف شد'
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد'
            ]);
        }
    }

    // get user duties
    public function getDuties(User $user)
    {
        $duties = $this->dutyService->getDuties($user);
        $categories = Category::all();

        if ($duties) {
            return response([
                'status' => 1,
                'msg' => 'وظایف کاربر',
                'duties' => new DutyCollection($duties),
                'user' => new UserResource($user),
                'categories' => $categories
            ]);
        } else {
            return response([
                'status' => 0,
                'msg' => 'کاربر یافت نشد'
            ]);
        }
    }
}
