<?php

namespace App\Http\Services\V1\Expert;

use App\Mail\MailVerificationCode;
use App\Models\Category;
use App\Models\User;
use App\Models\UserDuty;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use App\Providers\SMS;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DutyService
{
    public function saveDuty(Request $request, $id)
    {

        $logged_user = auth()->guard('api')->user();
        $user = User::find($id);

        if ($logged_user->users()->where('user_id', $id)->first()) {
//            $date = explode('-',$request->deadline);
//            $year = $date[0];
//            $month   = $date[1];
//            $day  = $date[2];
//
//            $deadline = JDF::jalali_to_gregorian($year , $month , $day , '-');

            $user->duties()->create([
                'title' => $request->title,
                'text' => $request->text,
                'deadline' => $request->deadline
            ]);

            $name = $user->firstname . " " . $user->lastname;
            $send = (new SMS())->sendVerification($user->mobile, "add_duty", "name=={$name}");

            //$send = User::sendMail(new MailVerificationCode("add_duty", [$name], "add_duty"), $user->email);


            return 1;
        } else {
            return 0;
        }
    }

    public function saveDutyAll(Request $request, $id)
    {

        $logged_user = auth()->guard('api')->user();
        $user = User::find($id);

        if ($logged_user->users()->where('user_id', $id)->first()) {

            UserDuty::where('user_id', $id)->delete();

            $category = Category::find($request->dutyTypeId);
            $type = explode(' ', $category->title)[0];
            $year = explode(' ', $category->title)[1];
            $year = explode('/', MyHelpers::numberToEnglish(JDF::gregorian_to_jalali((int)$year, 1, 1, '/')))[0];
            $user->duties()->create([
                'title' => 'تکمیل مدارک',
                'text' => '',
                'deadline' => $type === 'سامر' ? JDF::jalali_to_gregorian($year, 5, 15, '/') : JDF::jalali_to_gregorian($year, 11, 15, '/'),
                'apply_level_id' => 0
            ]);

            $user->duties()->create([
                'title' => 'ترجمه مدارک',
                'text' => 'ترجمه رسمی به زبان المانی با مهر دادگستری و وزارت امور خارجه',
                'deadline' => $type === 'سامر' ? JDF::jalali_to_gregorian($year, 6, 15, '/') : JDF::jalali_to_gregorian($year, 12, 15, '/'),
                'apply_level_id' => 72
            ]);

            $user->duties()->create([
                'title' => 'تایید و لگال مدارک',
                'text' => '',
                'deadline' => $type === 'سامر' ? JDF::jalali_to_gregorian($year, 7, 15, '/') : JDF::jalali_to_gregorian($year + 1, 1, 15, '/'),
                'apply_level_id' => 73
            ]);

            $user->duties()->create([
                'title' => 'اخذ مدرک زبان',
                'text' => '',
                'deadline' => $type === 'سامر' ? JDF::jalali_to_gregorian($year, 7, 15, '/') : JDF::jalali_to_gregorian($year + 1, 1, 15, '/'),
                'apply_level_id' => 0
            ]);

            $user->duties()->create([
                'title' => 'پست مدارک',
                'text' => 'ارسال پستی کپی های تایید شده توسط سفارت به کارشناس',
                'deadline' => $type === 'سامر' ? JDF::jalali_to_gregorian($year, 7, 19, '/') : JDF::jalali_to_gregorian($year + 1, 1, 20, '/'),
                'apply_level_id' => 111
            ]);

            $user->duties()->create([
                'title' => 'انتخاب دانشگاه ها',
                'text' => 'باز شدن لیست تمامی رشته دانشگاهای همخوان با شرایط با متقاضی توس کارشناس',
                'deadline' => $type === 'سامر' ? JDF::jalali_to_gregorian($year, 7, 20, '/') : JDF::jalali_to_gregorian($year + 1, 1, 20, '/'),
                'apply_level_id' => 0
            ]);

            $user->duties()->create([
                'title' => 'نگارش رزومه و انگیزه نامه',
                'text' => '',
                'deadline' => $type === 'سامر' ? JDF::jalali_to_gregorian($year, 7, 30, '/') : JDF::jalali_to_gregorian($year + 1, 1, 30, '/'),
                'apply_level_id' => 0
            ]);

            $user->duties()->create([
                'title' => 'پروسه اپلای',
                'text' => 'انجام پروسه اپلای توسط تیم اپلای جرمنی - امادگی برای پرداخت اپلیکیشن فی توسط کاربر',
                'deadline' => $type === 'سامر' ? JDF::jalali_to_gregorian($year, 7, 30, '/') : JDF::jalali_to_gregorian($year + 1, 1, 30, '/'),
                'apply_level_id' => 0
            ]);

            $user->duties()->create([
                'title' => 'اخذ اولین پذیرش',
                'text' => 'تاریخ مشخص شده حدودی است و بسته به زمان مورد نیاز دانشگاه دارد',
                'deadline' => $type === 'سامر' ? JDF::jalali_to_gregorian($year, 10, 30, '/') : JDF::jalali_to_gregorian($year + 1, 4, 30, '/'),
                'apply_level_id' => 0
            ]);

            $user->duties()->create([
                'title' => 'مصاحبه سفارت',
                'text' => 'تاریخ مشخص شده حدودی است و بسته به زمان اخذ پذیرش دارد',
                'deadline' => $type === 'سامر' ? JDF::jalali_to_gregorian($year, 11, 15, '/') : JDF::jalali_to_gregorian($year + 1, 5, 15, '/'),
                'apply_level_id' => 0
            ]);

            $user->duties()->create([
                'title' => 'اخذ ویزا',
                'text' => 'تاریخ مشخص شده حدودی است و بستگی به پروسه بررسی در اداره مهاجرت شهر مربوطه دارد',
                'deadline' => $type === 'سامر' ? JDF::jalali_to_gregorian($year + 1, 1, 1, '/') : JDF::jalali_to_gregorian($year + 1, 7, 1, '/'),
                'apply_level_id' => 0
            ]);

            $user->duties()->create([
                'title' => 'شروع ترم تحصیلی',
                'text' => '',
                'deadline' => $type === 'سامر' ? JDF::jalali_to_gregorian($year + 1, 1, 15, '/') : JDF::jalali_to_gregorian($year + 1, 7, 15, '/'),
                'apply_level_id' => 0
            ]);

            $name = $user->firstname . " " . $user->lastname;
            $send = (new SMS())->sendVerification($user->mobile, "add_duty", "name=={$name}");

            return 1;
        } else {
            return 0;
        }
    }

    public function updateDuty(Request $request, UserDuty $userDuty)
    {
        $logged_user = auth()->guard('api')->user();

        $user = $userDuty->user;

        if ($logged_user->users()->where('user_id', $user->id)->first()) {
//            $date = explode('-',$request->deadline);
//            $year = $date[0];
//            $month   = $date[1];
//            $day  = $date[2];
//
//            $deadline = JDF::jalali_to_gregorian($year , $month , $day , '-');

            $userDuty->update([
                'title' => $request->title,
                'text' => $request->text,
                'deadline' => $request->deadline
            ]);
            $name = $user->firstname . " " . $user->lastname;
            $send = (new SMS())->sendVerification($user->mobile, "edit_duty", "name=={$name}");
            //$send = User::sendMail(new MailVerificationCode("edit_duty", [$name], "edit_duty"), $user->email);

            return 1;
        } else {
            return 0;
        }
    }

    public function updateStatusDuty(Request $request, UserDuty $userDuty)
    {
        $logged_user = auth()->guard('api')->user();

        $user = $userDuty->user;

        if ($logged_user->users()->where('user_id', $user->id)->first()) {
            $userDuty->update([
                'status' => $request->status
            ]);

            return 1;
        } else {
            return 0;
        }
    }

    public function deleteDuty(UserDuty $userDuty)
    {
        $logged_user = auth()->guard('api')->user();

        $user = $userDuty->user;

        if ($logged_user->users()->where('user_id', $user->id)->first()) {
            $userDuty->delete();

            return 1;
        } else {
            return 0;
        }
    }

    public function getDuties(User $user)
    {
        $logged_user = auth()->guard('api')->user();

        if ($logged_user->users()->where('user_id', $user->id)->first()) {
            return $user->duties->sortByDesc("deadline");
        } else {
            return 0;
        }
    }

    function notyDoneDuty()
    {
        $duties = UserDuty::where('status', 1)->get();
        $client = new \GuzzleHttp\Client();

        foreach ($duties as $duty) {
            $date = Carbon::now()->addDays(1)->format('Y/M/d');
            $name = $duty->user->firstname . ' ' . $duty->user->lastname;
            $title = $duty->title;
            if ($title === 'تکمیل مدارک' || $title === 'ترجمه مدارک' || $title === 'تایید و لگال مدارک' || $title === 'اخذ مدرک زبان' || $title === 'پست مدارک')
                if ($duty->deadline === $date) {
                    $res = $client->request("POST", "https://chat.applygermany.net/notification", [
                        'json' => [
                            'to' => $duties->user_id,
                            'title' => "یاد آوری انجام وظیفه",
                            'body' => "${$name} عزیز زمان انجام فعالیت ${$title} فرا رسیده است.",
                            'arguments' => $duty,
                        ],
                        "headers" => [
                            "authentication" => "GKmxhXel5OiCG0Y8pnBPyOW8nx6SLobbPcr7MrS5tByvN1Vj7pCkfkfOx12UjgfcaBpOzzYTkGLkJCpHmav8PEN0viGnnDaRrz6J",
                        ],
                    ]);
                }
        }

    }
}
