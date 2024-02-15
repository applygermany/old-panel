<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Services\V1\User\AcceptanceService;
use App\Models\Acceptance;
use App\Models\UserTelSupport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcceptanceController extends Controller
{
    protected $acceptance;

    public function __construct(AcceptanceService $acceptance)
    {
        $this->acceptance = $acceptance;
    }

    public function acceptance()
    {
        $acceptance = $this->acceptance->acceptance();

        return response([
            'status' => 1,
            'msg' => 'درخواست پذیرش',
            'acceptance' => $acceptance
        ]);
    }

    public function submitStep1(Request $request)
    {
        $acceptance = $this->acceptance->submitStep1($request);

        if ($acceptance == 1)
            return response([
                'status' => 1,
                'msg' => 'اطلاعات ثبت شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'خطا در ثبت اطلاعات'
        ]);
    }

    public function submitStep2(Request $request)
    {
        $acceptance = $this->acceptance->submitStep2($request);

        if ($acceptance == 1)
            return response([
                'status' => 1,
                'msg' => 'اطلاعات ثبت شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'خطا در ثبت اطلاعات'
        ]);
    }

    function submitPackage(Request $request)
    {
        $acceptance = Acceptance::where('user_id', Auth::guard('api')->id())->where('last_form_submit', 1)->count();
        if ($acceptance == 0) {
            return response([
                'status' => 1,
                'hasTelSupport' => false
            ]);
        } else {
            $user = Auth::guard('api')->user();
            $user->type = $request->type;
            $user->max_university_count = $request->type === 2 ? 8 : 6;
            $user->package_at = Carbon::now();
            if ($user->save()) {
                return response([
                    'status' => 1,
                    'msg' => 'پکیج با موفقیت ثبت کردید',
                    'hasTelSupport' => true
                ]);
            } else {
                return response([
                    'status' => 0,
                    'msg' => 'ثبت پکیج با شکست مواجه کردید',
                    'hasTelSupport' => true
                ]);
            }
        }

    }

    public function submitContinueCollege(Request $request)
    {
        $acceptance = $this->acceptance->submitContinueCollege($request);

        if ($acceptance == 1)
            return response([
                'status' => 1,
                'msg' => 'اطلاعات ثبت شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'خطا در ثبت اطلاعات'
        ]);
    }

    public function submitStepBachelor(Request $request)
    {
        $acceptance = $this->acceptance->submitStepBachelor($request);

        if ($acceptance == 1)
            return response([
                'status' => 1,
                'msg' => 'اطلاعات ثبت شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'خطا در ثبت اطلاعات'
        ]);
    }

    public function submitStepMaster(Request $request)
    {
        $acceptance = $this->acceptance->submitStepMaster($request);

        if ($acceptance == 1)
            return response([
                'status' => 1,
                'msg' => 'اطلاعات ثبت شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'خطا در ثبت اطلاعات'
        ]);
    }

    public function submitMasterContinue(Request $request)
    {
        $acceptance = $this->acceptance->submitMasterContinue($request);

        if ($acceptance == 1)
            return response([
                'status' => 1,
                'msg' => 'اطلاعات ثبت شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'خطا در ثبت اطلاعات'
        ]);
    }

    public function submitStep3(Request $request)
    {
        $acceptance = $this->acceptance->submitStep3($request);

        if ($acceptance == 1)
            return response([
                'status' => 1,
                'msg' => 'اطلاعات ثبت شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'خطا در ثبت اطلاعات'
        ]);
    }

    public function submitStep4(Request $request)
    {
        $acceptance = $this->acceptance->submitStep4($request);

        if ($acceptance == 1)
            return response([
                'status' => 1,
                'msg' => 'اطلاعات ثبت شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'خطا در ثبت اطلاعات'
        ]);
    }

    public function submitStep5(Request $request)
    {
        $acceptance = $this->acceptance->submitStep5($request);

        if ($acceptance == 1)
            return response([
                'status' => 1,
                'msg' => 'اطلاعات ثبت شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'خطا در ثبت اطلاعات'
        ]);
    }

    public function submitStep6(Request $request)
    {
        $acceptance = $this->acceptance->submitStep6($request);

        if ($acceptance == 1)
            return response([
                'status' => 1,
                'msg' => 'اطلاعات ثبت شد'
            ]);
        return response([
            'status' => 0,
            'msg' => 'خطا در ثبت اطلاعات'
        ]);
    }
}
