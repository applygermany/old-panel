<?php

namespace App\Http\Services\V1\User;

use App\Mail\MailVerificationCode;
use App\Models\ApplyPhase;
use App\Models\ChangeRequest;
use App\Models\UserExtraUniversity;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Models\User;
use App\Models\UserApplyLevelStatus;
use App\Models\UserDuty;
use App\Models\UserSupervisor;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use App\Providers\SMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class DashboardService
{
    public function dashboard()
    {
        $user = User::where('id',auth()->guard('api')->id())->get();
        $acceptance = DB::select('select * from nag_acceptances where user_id = ' . auth()->guard('api')->id());
        if (count($acceptance) == 0)
            $acceptance[0] = NULL;
        $userApplyLevelStatus = DB::select('select * from nag_user_apply_level_status where user_id = ' . auth()->guard('api')->id());
        if (count($userApplyLevelStatus) == 0) {
            $userApplyLevelStatus = new UserApplyLevelStatus();
            $userApplyLevelStatus->user_id = auth()->guard('api')->id();
            $userApplyLevelStatus->save();
        }
        $userApplyLevelStatus = DB::select('select * from nag_user_apply_level_status where user_id = ' . auth()->guard('api')->id());
        foreach ($userApplyLevelStatus[0] as $key => $val) {
            if ($key == "phase1") {
                $phase = ApplyPhase::select("title", "description")->find(1);
                $userApplyLevelStatus[0]->$key = ["data" => $phase, "percent" => $val];
            }
            if ($key == "phase2") {
                $phase = ApplyPhase::select("title", "description")->find(2);
                $userApplyLevelStatus[0]->$key = ["data" => $phase, "percent" => $val];
            }
            if ($key == "phase3") {
                $phase = ApplyPhase::select("title", "description")->find(3);
                $userApplyLevelStatus[0]->$key = ["data" => $phase, "percent" => $val];
            }
            if ($key == "phase4") {
                $phase = ApplyPhase::select("title", "description")->find(4);
                $userApplyLevelStatus[0]->$key = ["data" => $phase, "percent" => $val];
            }
            if ($key == "phase5") {
                $phase = ApplyPhase::select("title", "description")->find(5);
                $userApplyLevelStatus[0]->$key = ["data" => $phase, "percent" => $val];
            }
        }
        $userDuties = UserDuty::orderBy('deadline', 'ASC')->where('user_id', auth()->guard('api')->id())->get();
        $userSupervisors = UserSupervisor::where('user_id', auth()->guard('api')->id())->get();
        $userSupervisorFirstTelSupport = 0;
        $userSupervisor[0] = NULL;
        $userSupport[0] = NULL;

        foreach ($userSupervisors as $_userSupervisor) {
            if ($_userSupervisor->supervisor->level === 5) {
                $userSupervisor = DB::select('select * from nag_users where id = ' . $_userSupervisor->supervisor_id);
                $select = "select * from `nag_user_tel_supports`";
                $select .= " where `nag_user_tel_supports`.`user_id` = " . auth()->guard('api')->id();
                $select .= " and `nag_user_tel_supports`.`supervisor_id` = " . $_userSupervisor->supervisor_id;
                $userSupervisorFirstTelSupport = DB::select($select);
                if (isset($userSupervisorFirstTelSupport[0]))
                    $userSupervisorFirstTelSupport = 1;
            }
            if ($_userSupervisor->supervisor->level === 2) {
                $userSupport = DB::select('select * from nag_users where id = ' . $_userSupervisor->supervisor_id);
            }
        }

        $transactions = Invoice::orderBy('id','desc')->where('user_id', auth()->guard('api')->id())->get();
        $isBalanced = !Invoice::where('user_id', auth()->guard('api')->id())->where('payment_status', 'unpaid')->count();
        $trans_euro = 0;
        foreach (Invoice::where('user_id', auth()->guard('api')->id())->where('euro_amount', '<>' , '0')->where('payment_status', 'unpaid')->get() as $trans) {
            $trans_euro += $trans->final_amount;
        }

        $trans_ir = 0;
        foreach (Invoice::where('user_id', auth()->guard('api')->id())->where('ir_amount', '<>', '0')->where('euro_amount', '0')->where('payment_status', 'unpaid')->get() as $trans) {
            $trans_ir += $trans->final_amount;
        }

        $users = DB::select('select * from nag_users where user_id = ' . auth()->guard('api')->id());


        $users1 = User::where('user_id',  auth()->guard('api')->id())->get();
        $balance = 0;
        $balance2 = 0;
        foreach ($users1 as $item) {
            $invoice = Invoice::where('user_id', $item->id)->where('invoice_type', 'final')->where('payment_status', 'paid')->first();
            if ($invoice) {
                $balance += intval(Pricing::first()->invite_action);
                $balance2 += intval(Pricing::first()->invite_action);
            } else {
                $balance2 += intval(Pricing::first()->invite_action);
            }
        }
        if ($user[0]->balance - $balance2 > 0){
            $balance = $balance + ($user[0]->balance - $balance2);
        }elseif($user[0]->balance < 0){
            $balance = $user[0]->balance;
        }
        return [
            'user' => $user[0],
            'acceptance' => $acceptance[0],
            'userApplyLevelStatus' => $userApplyLevelStatus[0],
            'userDuties' => $userDuties,
            'userSupervisorFirstTelSupport' => $userSupervisorFirstTelSupport,
            'userSupervisor' => $userSupervisor[0],
            'userSupport' => $userSupport[0],
            'transactions' => $transactions,
            "balance" => [$isBalanced, $trans_euro, $trans_ir, $balance],
            'users' => $users,
        ];
    }

    public function updateProfile(Request $request)
    {

        auth()->guard('api')->user()->firstname = $request->firstname;
        auth()->guard('api')->user()->lastname = $request->lastname;
        auth()->guard('api')->user()->firstname_en = $request->firstnameEn;
        auth()->guard('api')->user()->lastname_en = $request->lastnameEn;
        if ($request->birthDate) {
            auth()->guard('api')->user()->birth_date = explode("/", $request->birthDate)[0] < 1500 ? JDF::jalali_to_gregorian_($request->birthDate, "-") : $request->birthDate . " 00:00:00";
        }
        auth()->guard('api')->user()->codemelli = $request->codemelli;
        if (auth()->guard('api')->user()->save()) {
            return 1;
        }
        return 0;
    }

    public function uploadImage(Request $request)
    {
        if ($request->file('image')) {
            auth()->guard('api')->user()->touch();
            $folder = '/uploads/avatar/';
            $file = $request->file('image');
            $file->move(public_path() . $folder, auth()->guard('api')->id() . '.jpg');
            return 1;
        }
        return 0;
    }

    public function changeEmailMobile(Request $request)
    {
        if ($request->newEmail) {
            $request->newEmail = strtolower(MyHelpers::numberToEnglish($request->newEmail));
            $user = User::where('email', $request->newEmail)->where('id', '!=', auth()->guard('api')->id())->first();
            if ($user)
                return 2;
        }
        if ($request->newMobile) {
            $request->newMobile = MyHelpers::numberToEnglish($request->newMobile);
            if (strlen($request->newMobile) > 10)
                $request->newMobile = substr($request->newMobile, 1, 10);
            $user = User::where('mobile', $request->newMobile)->where('id', '!=', auth()->guard('api')->id())->first();
            if ($user)
                return 3;
        }
        $changeRequest = ChangeRequest::where('user_id', auth()->guard('api')->id())->first();
        if (!$changeRequest)
            $changeRequest = new ChangeRequest();
        $changeRequest->user_id = auth()->guard('api')->id();
        $code = rand(9999, 99999);
        if ($request->newEmail) {
            $changeRequest->email = $request->newEmail;
            $send = User::sendMail(new MailVerificationCode("signup", [$code], "signup"), $request->newEmail);
            $changeRequest->mobile = NULL;
        } else
            $changeRequest->email = NULL;
        if ($request->newMobile) {
            $changeRequest->mobile = $request->newMobile;
            $send = (new SMS())->sendVerification($request->newMobile, "signup", "VerificationCode==" . $code);
            $changeRequest->email = NULL;
        } else
            $changeRequest->mobile = NULL;
        $changeRequest->code = bcrypt($code);
        if ($changeRequest->save())
            return 1;
        return 0;
    }

    public function changeEmailMobileVerify(Request $request)
    {
        $changeRequest = ChangeRequest::where('user_id', auth()->guard('api')->id())->first();
        if ($changeRequest) {
            if ($changeRequest->email != NULL) {
                $user = User::where('email', $changeRequest->email)->where('id', '!=', auth()->guard('api')->id())->first();
                if ($user) {
                    $changeRequest->delete();
                    return 4;
                }
            }
            if ($changeRequest->mobile != NULL) {
                $user = User::where('mobile', $changeRequest->mobile)->where('id', '!=', auth()->guard('api')->id())->first();
                if ($user) {
                    $changeRequest->delete();
                    return 3;
                }
            }
            if (!Hash::check($request->code, MyHelpers::numberToEnglish($changeRequest->code)))
                return 2;
            if ($changeRequest->mobile != NULL)
                auth()->guard('api')->user()->mobile = $changeRequest->mobile;
            if ($changeRequest->email != NULL)
                auth()->guard('api')->user()->email = $changeRequest->email;
            if (auth()->guard('api')->user()->save()) {
                $changeRequest->delete();
                return 1;
            }
        }
        return 0;
    }

    public function changeEmailMobileResendCode()
    {
        $code = rand(9999, 99999);
        $changeRequest = ChangeRequest::where('user_id', auth()->guard('api')->id())->first();
        if ($changeRequest) {
            if ($changeRequest->mobile) {
                $send = (new SMS())->sendVerification($changeRequest->mobile, "signup", "VerificationCode==" . $code);
            } else {

//				$send = User::sendMail(new MailVerificationCode("signup", [
//					$code,
//				], "signup"), $changeRequest->email);
            }
            $changeRequest->code = bcrypt($code);
            if ($changeRequest->save())
                return 1;
        }
        return 0;
    }

    public function updatePassword(Request $request)
    {
        $request->oldPassword = MyHelpers::numberToEnglish($request->oldPassword);
        if (!Hash::check($request->oldPassword, MyHelpers::numberToEnglish(auth()->guard('api')->user()->password)))
            return 2;
        auth()->guard('api')->user()->password = bcrypt($request->newPassword);
        if (auth()->guard('api')->user()->save())
            return 1;
        return 0;
    }

    public function changeDarkMode()
    {
        if (auth()->guard('api')->user()->darkmode == 1)
            auth()->guard('api')->user()->darkmode = 0;
        else
            auth()->guard('api')->user()->darkmode = 1;
        auth()->guard('api')->user()->save();
        return 1;
    }
}
