<?php

namespace App\Http\Controllers\Admin;

use App\Mail\MailVerificationCode;
use App\Models\Comment;
use App\Models\ExpertTag;
use App\Models\Pricing;
use App\Models\TelSupport;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use App\Models\User;
use App\Models\UserComment;
use App\Models\UserTelSupport;
use App\Providers\Notification;
use App\Providers\SMS;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TelSupportController extends Controller
{
    function telSupportExpert()
    {
        $experts = User::whereIn('level', [5, 7, 3])->where('status', 1)->get();
        return view('admin.telSupports.experts', compact('experts'));
    }

    function telSupportsExpertTimes($id)
    {
        $expert = User::find($id);
        $t = UserTelSupport::all()->pluck('tel_support_id');
        $times = TelSupport::where('user_id', $id)->whereNotIn('id', $t)->get();
        $users = User::where('level', 1)->get();

        return view('admin.telSupports.times', compact('expert', 'times', 'users'));
    }

    function telSupportsExpertGetTimes(Request $request)
    {
        $expert = User::find($request->id);
        $t = UserTelSupport::all()->pluck('tel_support_id');
        $times = TelSupport::where('user_id', $request->id)->whereNotIn('id', $t)->get();
        $users = User::where('level', 1)->get();

        return view('admin.telSupports.partials.time-list', compact('expert', 'times', 'users'));
    }

    function telSupportsReserveTime(Request $request, $id)
    {
        $date = explode('-', $request->date);
        $gDate = JDF::jalali_to_gregorian($date[0], $date[1], $date[2], '-');

        $expert = User::find($id);

        $telSupport = new TelSupport();
        $telSupport->day_tel_fa = $request->date;
        $telSupport->day_tel = $gDate;
//        example:
//        1:1
//        1 =>1:00
//        13=>13:00
        if(strlen($request->fromTime) >= 2 and str_contains($request->fromTime,':')){
            $telSupport->from_time = $request->fromTime;
        }else{
            if($request->fromTime){
                $telSupport->from_time=$request->fromTime.':00';
            }else{
//                toastr()->error('زمان های رزرو را به درستی انتخاب کنید');
                return redirect()->back();
            }
        }
        if(strlen($request->toTime) >= 2 and str_contains($request->toTime,':')){
            $telSupport->to_time = $request->toTime;
        }else{
            if($request->toTime){
                $telSupport->to_time=$request->toTime.':00';
            }else{
//                toastr()->error('زمان های رزرو را به درستی انتخاب کنید');
                return redirect()->back();
            }
        }
//        $telSupport->to_time = $request->toTime;
        $telSupport->user_id = $id;
        $telSupport->price = $expert->level === 3 ? '0' : Pricing::first()->tel_maximum_price;
        $telSupport->status = 1;
        $telSupport->type = 2;

        if ($telSupport->save()) {

            $userTelSupport = new UserTelSupport();
            $userTelSupport->tel_support_id = $telSupport->id;
            $userTelSupport->supervisor_id = $id;
            $userTelSupport->user_id = $request->user;
            $userTelSupport->title = $request->title;
            $userTelSupport->tel_date = $gDate;
            $userTelSupport->save();


            $user = User::find($request->user);
            $name = $user->firstname . " " . $user->lastname;
            //to user
            $send = (new SMS())->sendVerification($user->mobile, "tel_reserved", "name=={$name}&date==" . $userTelSupport->telSupport->day_tel_fa . "&clock_1==" . $userTelSupport->telSupport->from_time .
                "&clock_2==" . $userTelSupport->telSupport->to_time . "&mobile_supervisor==" . $userTelSupport->supervisor->mobile);
            $send = User::sendMail(new MailVerificationCode("tel_reserved", [
                $name,
                $userTelSupport->telSupport->day_tel_fa,
                $userTelSupport->telSupport->from_time,
                $userTelSupport->telSupport->to_time,
                $userTelSupport->supervisor->mobile,
            ], "tel_reserved"), $user->email);
            $notif = (new Notification("tel_reserved", [
                $name,
                $userTelSupport->telSupport->day_tel_fa,
                $userTelSupport->telSupport->from_time,
                $userTelSupport->telSupport->to_time,
                $userTelSupport->supervisor->mobile,
            ]))->send($user->id);
            $tel = $userTelSupport->telSupport;

            //to  expert
            if ($tel->user->id !== 26) {
                $notif = (new Notification("expert_tel_reserved", [
                    $tel->user->firstname,
                    $tel->day_tel_fa,
                    $tel->from_time,
                    $tel->to_time,
                ]))->send($tel->user->id);

                $send = User::sendMail(new MailVerificationCode("expert_tel_reserved", [
                    $tel->user->firstname,
                    $tel->day_tel_fa,
                    $tel->from_time,
                    $tel->to_time,
                ], "expert_tel_reserved"), $tel->user->email);
            }

            session()->flash('success', 'ثبت رزرو با موفقیت انجام گردید');
        } else {
            session()->flash('error', 'ثبت رزرو با شکست مواجه گردید');
        }
        return redirect()->back();
    }

    function telSupportsExpertChooseTime($id)
    {
        $telSupport = TelSupport::find($id);
        $users = User::where('level', 1)->get();
        return view('admin.telSupports.partials.choose', compact('telSupport', 'users'))->render();
    }

    function telSupportsExpertReserveTime(Request $request)
    {
        $telSupport = TelSupport::find($request->id);
        $telSupport->type = 2;

        if ($telSupport->save()) {
            $userTelSupport = new UserTelSupport();
            $userTelSupport->tel_support_id = $telSupport->id;
            $userTelSupport->supervisor_id = $telSupport->user->id;
            $userTelSupport->user_id = $request->user;
            $userTelSupport->title = $request->title;
            $userTelSupport->tel_date = $telSupport->day_tel;
            $userTelSupport->save();


            $user = User::find($request->user);
            $name = $user->firstname . " " . $user->lastname;
            //to user
            $send = (new SMS())->sendVerification($user->mobile, "tel_reserved", "name=={$name}&date==" . $userTelSupport->telSupport->day_tel_fa . "&clock_1==" . $userTelSupport->telSupport->from_time .
                "&clock_2==" . $userTelSupport->telSupport->to_time . "&mobile_supervisor==" . $userTelSupport->supervisor->mobile);
            $send = User::sendMail(new MailVerificationCode("tel_reserved", [
                $name,
                $userTelSupport->telSupport->day_tel_fa,
                $userTelSupport->telSupport->from_time,
                $userTelSupport->telSupport->to_time,
                $userTelSupport->supervisor->mobile,
            ], "tel_reserved"), $user->email);
            $notif = (new Notification("tel_reserved", [
                $name,
                $userTelSupport->telSupport->day_tel_fa,
                $userTelSupport->telSupport->from_time,
                $userTelSupport->telSupport->to_time,
                $userTelSupport->supervisor->mobile,
            ]))->send($user->id);
            $tel = $userTelSupport->telSupport;

            //to  expert
            if ($tel->user->id !== 26) {
                $notif = (new Notification("expert_tel_reserved", [
                    $tel->user->firstname,
                    $tel->day_tel_fa,
                    $tel->from_time,
                    $tel->to_time,
                ]))->send($tel->user->id);

                $send = User::sendMail(new MailVerificationCode("expert_tel_reserved", [
                    $tel->user->firstname,
                    $tel->day_tel_fa,
                    $tel->from_time,
                    $tel->to_time,
                ], "expert_tel_reserved"), $tel->user->email);
            }

            return 1;
        } else {
            return 0;
        }
    }

}
