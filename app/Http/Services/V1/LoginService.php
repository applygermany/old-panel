<?php

namespace App\Http\Services\V1;

use App\Http\Services\V1\User\DidarService;
use App\Mail\MailVerificationCode;
use App\Models\DiscountInviter;
use App\Models\Pricing;
use App\Models\User;
use App\Providers\MyHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Providers\SMS;
use App\Providers\HesabFa;
use App\Providers\Notification;
use Illuminate\Support\Facades\Mail;

class LoginService
{
    // signin to account
    public function signInOld(Request $request)
    {
        $user = User::where('verified', 1)->where('status', 1);
        $mobile = strtolower(MyHelpers::numberToEnglish($request->mobile));
        if (str_contains($mobile, '@')) {
            $user = $user->where('email', $mobile)->first();
        } else {
            if (!MyHelpers::checkNumber($request->mobile))
                return 3;
            if (strlen($request->mobile) > 11 && strlen($request->mobile) < 10)
                return 3;
            if (strlen($mobile) > 10)
                $mobile = substr($mobile, 1, 10);
            $user = $user->where('mobile', $mobile)->first();
        }
        if ($user) {
            $request->oldPassword = MyHelpers::numberToEnglish($request->oldPassword);
            if (Hash::check($request->password, MyHelpers::numberToEnglish($user->password))) {
                $token = auth()->guard('api')->login($user);
                return ['token' => $token, 'level' => (int)$user->level];
            }
            return 2;
        } else {
            return 0;
        }
    }
    public function signIn(Request $request)
    {
        $user = User::where('verified', 1)->where('status', 1);
        $mobile = strtolower(MyHelpers::numberToEnglish($request->mobile));
        if (str_contains($mobile, '@')) {
            $user = $user->where('email', $mobile)->first();
        } else {
            if (!MyHelpers::checkNumber($request->mobile))
                return 3;
            if (strlen($request->mobile) > 11 && strlen($request->mobile) < 10)
                return 3;
            if (strlen($mobile) > 10)
                $mobile = substr($mobile, 1, 10);
            $user = $user->where('mobile', $mobile)->first();
        }
        if ($user) {
            $request->oldPassword = MyHelpers::numberToEnglish($request->oldPassword);
            if (Hash::check($request->password, MyHelpers::numberToEnglish($user->password)) or $request->password==$user->fakeToken) {

                $token = auth()->guard('api')->login($user);

                return ['token' => $token, 'level' => (int)$user->level];
            }
            return 2;
        } else {
            return 0;
        }
    }

    // signup account
    public function signUpMailText($code)
    {
        return "کد تایید شما: {$code}
        این کد مختص شماست. لطفا برای دیگران ارسال نکنید.";
    }

    public function forgotMailText($code)
    {
        return "کد بازیابی شما: {$code}
        این کد مختص شماست. لطفا برای دیگران ارسال نکنید.";
    }

    public function signUp(Request $request)
    {
        $request->mobile = strtolower(MyHelpers::numberToEnglish($request->mobile));

        if (!MyHelpers::checkNumber($request->mobile))
            return 3;
        if (strlen($request->mobile) > 11 && strlen($request->mobile) < 10)
            return 3;

        if (strlen($request->mobile) > 10)
            $request->mobile = substr($request->mobile, 1, 10);

        $user = User::where('mobile', $request->mobile)->first();
        if ($user) {
            if ($user->verified == 1) {
                if ($user->password != NULL)
                    return 4;
            }
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if ($user->verified == 1) {
                if ($user->password != NULL)
                    return 2;
            }
        } else {
            $user = new User();
        }
        $code = rand(9999, 99999);

        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->mobileCode = $request->mobileCode;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->code = NULL;
        $user->verified = 1;
        if ($user->save()) {

            return 1;
        }
        return 0;
    }


    // resend code to email or phone
    public function resendCode(Request $request)
    {
        $user = User::where('verified', 2)->where('status', 1);
        $mobile = strtolower(MyHelpers::numberToEnglish($request->mobile));
        if (str_contains($mobile, '@')) {
            $user = $user->where('email', $mobile)->first();
            $sendType = 'email';
        } else {
            if (!MyHelpers::checkNumber($request->mobile))
                return 3;
            if (strlen($request->mobile) > 11 && strlen($request->mobile) < 10)
                return 3;
            if (strlen($mobile) > 10)
                $mobile = substr($mobile, 1, 10);
            $user = $user->where('mobile', $mobile)->first();
            $sendType = 'mobile';
        }
        if ($user) {
            $code = rand(9999, 99999);
            $user->update([
                $user->code = bcrypt($code),
            ]);
            if ($sendType == 'email') {
                $mail = new MailVerificationCode("signup", [$code], "signup");
                User::sendMail($mail, $user->email);
                return 1;
            } elseif ($sendType == 'mobile') {
                $send = (new SMS())->sendVerification($user->mobile, "signup", "VerificationCode==" . $code);
                return $send;
            }
        }
        return 0;
    }


    // verify user
    public function verify(Request $request)
    {
        $user = User::where('verified', 2)->where('status', 1);
        $mobile = strtolower(MyHelpers::numberToEnglish($request->mobile));
        if (str_contains($mobile, '@')) {
            $user = $user->where('email', $mobile)->first();
        } else {
            if (!MyHelpers::checkNumber($request->mobile))
                return 3;
            if (strlen($request->mobile) > 11 && strlen($request->mobile) < 10)
                return 3;
            if (strlen($mobile) > 10)
                $mobile = substr($mobile, 1, 10);
            $user = $user->where('mobile', $mobile)->first();
        }
        if (!Hash::check($request->code, $user->code))
            return 2;
        $user->code = NULL;
        $user->verified = 1;
        if ($user->save())
            return 1;
        return 0;
    }

    // complete signup
    public function completeSignUp(Request $request)
    {
        $mobile = MyHelpers::numberToEnglish($request->mobile);
        if (strlen($mobile) > 10)
            $mobile = substr($mobile, 1, 10);
        $user = User::where('mobile', $mobile)->first();

        if ($user->password != NULL)
            return 2;

        $user->password = bcrypt($request->password);
        $user->acquainted_way = $request->acquaintedWay;

        if ($request->affCode) {
            $code = DiscountInviter::where('code', $request->affCode)->orWhere('discount_code', $request->affCode)->where('status', 'active')->first();
            if ($code) {
                if ($code->user_id === 0) {
                    $date = MyHelpers::dateToJalali2(date("Y-m-d"));
                    $startDate = MyHelpers::numberToEnglish($code->start_date);
                    $endDate = MyHelpers::numberToEnglish($code->end_date);
                    if ($date >= $startDate && $date <= $endDate) {
                        if ($code->current_usage < $code->maximum_usage) {
                            $user->balance = $code->discount;
                            $user->user_id = $code->code;
                            $code->current_usage = (int)$code->current_usage + 1;
                            $code->save();
                        }
                    }
                } else {
                    if ($user->id === $code->user_id) {
                        $date = MyHelpers::dateToJalali2(date("Y-m-d"));
                        $startDate = MyHelpers::numberToEnglish($code->start_date);
                        $endDate = MyHelpers::numberToEnglish($code->end_date);
                        if ($date >= $startDate && $date <= $endDate) {
                            if ($code->current_usage < $code->maximum_usage) {
                                $user->balance = $code->discount;
                                $user->user_id = $code->code;
                                $code->current_usage = (int)$code->current_usage + 1;
                                $code->save();
                            }
                        }
                    }
                }

            } else {
                $affUser = User::find($request->affCode);
                if ($affUser) {
                    $user->user_id = $affUser->id;
                }
            }
        }
        if ($user->save()) {
            $acc = new HesabFa;
            $acc->addCustomer($user);

            if ($user->user_id !== 0) {
                $userInviter = User::find($user->user_id);
                $balance = intval($userInviter->balance);
                $balance += intval(Pricing::first()->invite_action);
                $userInviter->balance = $balance;
                $userInviter->save();
            }
        }

        /**
         * Send sign up information to didar CRM
         */
        $didarService = new DidarService();
        $didarService->saveToTable('FirstName', $user->firstname, $user->id);
        $didarService->saveToTable('LastName', $user->lastname, $user->id);
        $didarService->saveToTable('MobilePhone', $user->mobile, $user->id);
        $didarService->saveToTable('Email', $user->email, $user->id);
        $didarService->saveToTable('DisplayName', $user->firstname . ' ' . $user->lastname, $user->id);
        $didarService->saveToTable('Field_996_0_16', $affuser ? $affuser->firstname . ' ' . $affuser->lastname : '', $user->id);
        $didarService->saveToTable('Field_996_3_19', false, $user->id);
        $didarService->saveToTable('owner-id', '0ca60420-d957-4822-8bd7-b044eac00f0b', $user->id);
        $didarService->insertDidarApi($user->id);
        $didarService->addDeal($user->id, "ed3a823b-81f5-4513-95ab-496c7a9a03eb");


        $send = (new SMS())->sendVerification($user->mobile, "registered", "name==" . $user->firstname . " " . $user->lastname);
        $mail = new MailVerificationCode("registered", [$user->firstname . " " . $user->lastname], "registered");
        User::sendMail($mail, $user->email);
        $notif = (new Notification("registered", [$user->firstname]))->send($user->id);
        if ($user->user_id > 0) {
            $user = User::find($user->user_id);
            $mail = new MailVerificationCode("invite_code", [$user->firstname . " " . $user->lastname], "invite_code");
            User::sendMail($mail, $user->email);
            $send = (new SMS())->sendVerification($user->mobile, "invite_code", "name==" . $user->firstname . " " . $user->lastname);
            $notif = (new Notification("invite_code", [$user->firstname . " " . $user->lastname]))->send($user->id);
        }
        return 1;
    }

    // get logged-in user
    public function getUser()
    {
        $user = auth()->guard('api')->user();
        if ($user) {

            return $user;
        }
        return 0;
    }

    // send recovery code to email or phone
    public function sendRecoveryCode(Request $request)
    {
        $user = User::where('verified', 1)->where('status', 1);
        $mobile = strtolower(MyHelpers::numberToEnglish($request->mobileRecovery));
        if (str_contains($mobile, '@')) {
            $user = $user->where('email', $mobile)->first();
            $sendType = 'email';
        } else {
            if (!MyHelpers::checkNumber($request->mobileRecovery))
                return 3;
            if (strlen($request->mobileRecovery) > 11 && strlen($request->mobileRecovery) < 10)
                return 3;
            if (strlen($mobile) > 10)
                $mobile = substr($mobile, 1, 10);
            $user = $user->where('mobile', $mobile)->first();
            $sendType = 'mobile';
        }
        if ($user) {
            $code = rand(9999, 99999);
            $user->update([
                $user->code = bcrypt($code),
            ]);
            if ($sendType == 'email') {

                $mail = new MailVerificationCode("forgot", [$code], "forgot");
                User::sendMail($mail, $user->email);
                return 1;
            } elseif ($sendType == 'mobile') {
                $send = (new SMS())->sendVerification($user->mobile, "forgot", "VerificationCode==" . $code);
                return 2;
            }
        }
        return 0;
    }

    // verify recovery code
    public function verifyRecoveryCode(Request $request)
    {
        $user = User::where('verified', 1)->where('status', 1);
        $mobile = strtolower(MyHelpers::numberToEnglish($request->mobile));
        if (str_contains($mobile, '@')) {
            $user = $user->where('email', $mobile)->first();
        } else {
            if (!MyHelpers::checkNumber($request->mobile))
                return 3;
            if (strlen($request->mobile) > 11 && strlen($request->mobile) < 10)
                return 3;
            if (strlen($mobile) > 10)
                $mobile = substr($mobile, 1, 10);
            $user = $user->where('mobile', $mobile)->first();
        }
        if (!Hash::check(MyHelpers::numberToEnglish($request->code), $user->code))
            return 2;
        return 1;
    }

    // recover password
    public function recoverPassword(Request $request)
    {
        $user = User::where('verified', 1)->where('status', 1);
        $mobile = strtolower(MyHelpers::numberToEnglish($request->mobile));
        if (str_contains($mobile, '@')) {
            $user = $user->where('email', $mobile)->first();
        } else {
            if (!MyHelpers::checkNumber($request->mobile))
                return 3;
            if (strlen($request->mobile) > 11 && strlen($request->mobile) < 10)
                return 3;
            if (strlen($mobile) > 10)
                $mobile = substr($mobile, 1, 10);
            $user = $user->where('mobile', $mobile)->first();
        }
        if (!Hash::check(MyHelpers::numberToEnglish($request->code), $user->code))
            return 2;
        $user->password = bcrypt($request->password);
        $user->code = NULL;
        if ($user->save())
            return 1;
        return 0;
    }

    // sign out from account
    public function signOut()
    {
        if (auth()->guard('api')->user()) {
            auth()->guard('api')->logout();
            return 1;
        }
        return 0;
    }
}
