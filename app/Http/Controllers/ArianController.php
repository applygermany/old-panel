<?php

namespace App\Http\Controllers;

use App\ExcelExports\Users;
use App\Mail\MailVerificationCode;
use App\Models\ResumeCourse;
use App\Models\ResumeEducationRecord;
use App\Models\ResumeHobby;
use App\Models\ResumeLanguage;
use App\Models\ResumeResearch;
use App\Models\ResumeSoftwareKnowledge;
use App\Models\ResumeWork;
use App\Models\User;
use App\Models\UserSupervisor;
use App\Models\UserTelSupport;
use App\Models\UserWebinar;
use App\Providers\MyHelpers;
use App\Providers\Notification;
use App\Providers\SMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ResumeMotivationIds;
use Illuminate\Support\Facades\Http;
use PDF;
class ArianController extends Controller
{
    //

    public function calcAllPrices(){
        UserWebinar::where('id','>=', 1577)->whereNotIn('id', function ($query) {
            $query->select(DB::raw('MAX(id)'))
                ->from('user_webinars')
                ->groupBy('mobile');
        })->delete();

        $webinar=UserWebinar::where('id','>=', 1577)->get();
        $totalPrice=0;
        foreach ($webinar as $w){
            $totalPrice+=$w['price'];
        }
        return $totalPrice .'تومان';
    }
    public function generatePurePdf(){
        $invoice='';
        $banks='';
        $extraUniversities=null;
        $pdf = PDF::loadView('pure-1300-contract', [
            'invoice' => $invoice,
            'banks' => $banks
        ], ['extra_universities' => $extraUniversities], [
            'subject' => 'قرارداد 1300'
        ]);
        return $pdf->stream(1919 . ' ' . time() . '.pdf');
    }

    public function sendSmsToWebinarUsers(){

    }


    function checkEmail($name, $lastname, $sendToEmail)
    {
        $data=['hey'=>['salam','hello'],'hello'=>['salam','hello']];
        $mail = User::sendMail(new MailVerificationCode("add_writer", [
            $name . " " . $name,
            'انگیزه نامه'
        ], "add_writer"),public_path('uploads/invoices/531.pdf'), $sendToEmail);
//        foreach ($data as $d){
//            try {
//                $send = User::sendMail(new MailVerificationCode("admin_motivation_added", [
//                    $name,
//                    112,
//                ], "admin_motivation_added"), $sendToEmail);
//            } catch (\Exception $e) {
//                // Handle the exception (e.g., log the error)
//                \Log::error("Error sending email to {$sendToEmail}: " . $e->getMessage());
//
//                // You can choose to continue with the next iteration or skip it
//                continue; // or use "break;" if you want to stop the loop entirely on error
//            }
//        }


//        $mail = new MailVerificationCode("invite_code", [$name . " " . $lastname], "invite_code");
//        User::sendMail($mail, $sendToEmail);
    }

    public function checkSms()
    {
        $mobile = '09126664936';
        $name = 'arian';
        $date = '1402/02/03';
        $time = '12:30';
        $send = (new SMS())->sendVerification($mobile, "tel_24h_remained", "name=={$name}&date==" . $date . "&clock_1==" . $time .
            "&clock_2==" . $time);
        return $send;
    }

    public function checkSmsNew()
    {
//        $url='http://ippanel.com:8080/?apikey=sHlP71Z5DrRSAHwgnOJjwmF0vFn8Zo8tc9CwCq73r_U=&pid=zmmg0x4cg6u1tm0&fnum=+983000505&tnum=+989126664936&p1=name&p2=date&p3=clock_1&p4=clock_2&v1=arian&v2=1402&v3=12:30&v4=12:50';
//        $response = Http::get($url);
        $mobile = '9126664936';
        $name = 'آری';
        $date = '1402';
        $time = '12:15';
        $time2 = '13:30';
        $parameter = [
            'names' => ['name', 'date', 'clock_1', 'clock_2'],
            'values' => [$name, $date, $time, $time2]
        ];
        $send = (new SMS())->sendUltraFastArian($mobile, "tel_24h_remained", $parameter);
        return $send;
    }

    public function doTheDebug($id)
    {
        if ($id == '196188482letsCleanUpChildishBugs') {
            UserSupervisor::whereNotIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('user_supervisors')
                    ->groupBy('user_id', 'supervisor_id');
            })->delete();
            ResumeMotivationIds::whereNotIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('resume_motivation_ids')
                    ->groupBy('user_id', 'model_id', 'model_type');
            })->delete();
        } else {
            return 'shame on you';
        }
    }

    public function removeSameRowFromTblResumeEducationRecords($id, $hash)
    {
        if ($hash == '196188482letsCleanUpChildishBugs') {
            ResumeEducationRecord::where('resume_id', $id)->whereNotIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('resume_education_records')
                    ->groupBy('resume_id', 'grade', 'from_date_year', 'from_date_month', 'to_date_month', 'school_name', 'field', 'grade_score', 'city', 'text');
            })->delete();
            ResumeWork::where('resume_id', $id)->whereNotIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('resume_works')
                    ->groupBy('resume_id', 'from_date_year', 'from_date_month', 'to_date_year', 'to_date_month', 'company_name', 'city', 'position', 'city', 'text');
            })->delete();
            ResumeResearch::where('resume_id', $id)->whereNotIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('resume_researchs')
                    ->groupBy('resume_id', 'type', 'title', 'year', 'text');
            })->delete();
            ResumeLanguage::where('resume_id', $id)->whereNotIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('resume_languages')
                    ->groupBy('resume_id', 'fluency_level', 'title', 'degree', 'score', 'current_status');
            })->delete();
            ResumeHobby::where('resume_id', $id)->whereNotIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('resume_hobbies')
                    ->groupBy('resume_id', 'title');
            })->delete();
            ResumeCourse::where('resume_id', $id)->whereNotIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('resume_courses')
                    ->groupBy('resume_id', 'title', 'organizer', 'year');
            })->delete();
            ResumeSoftwareKnowledge::where('resume_id', $id)->whereNotIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('resume_software_knowledges')
                    ->groupBy('resume_id', 'title', 'fluency_level');
            })->delete();
        } else {
            return 'false information';
        }
    }

    public function getUsersExel()
    {
        $users = User::where([['level', 1], ['type', 1], ['mobile', '<>', null], ['mobile', '!=', '']])->select('id', 'firstname', 'lastname', 'mobile', 'created_at')->doesntHave('userTelSupports')->get();
//    return MyHelpers::dateToJalali($users[0]['created_at']);

        //        $strToTime=strtotime($users[0]['created_at']);
//        return $strToTime;
        $filterUsers = [];
        foreach ($users as $key => $value) {
            $jalaliDate = MyHelpers::dateToJalali($users[$key]['created_at']);
            $arrayDate = explode('/', $jalaliDate);
            $users[$key]['jalaliDate'] = $jalaliDate;
            if ($arrayDate[0] == 1402) {
                if ($arrayDate[1] >= 07) {
                    array_push($filterUsers, $users[$key]);
                }
            }
        }
        $fake = ["searchFirstname" => null, "searchLastname" => null,
            "fromdate" => null,
            "todate" => null,
            "searchType" => null,
            "searchPhone" => null,
            "users" => $filterUsers
        ];

        $request = new Request($fake);
        return Excel::download(new Users($request), 'users.xlsx');
    }

    public function test()
    {
        return User::where('id', 21368)->first();
    }

    public function reminderEmail()
    {
        $Usupps = UserTelSupport::where("user_id", ">", 0)->get();
        foreach ($Usupps as $UtelSupp) {
            $tel = $UtelSupp->telSupport;
            $strTime = $tel->day_tel . '' . $tel->from_time;
            $strToTime = strtotime($strTime);
            $strToTimeMinuten = ($strToTime - time()) / 60;
            $roundAction = round(round(($strToTime - time()) / 60, 0) / 2, 0);
            if ($strToTimeMinuten < 120 and $tel->has_been_sent == 0 and $strToTimeMinuten > 0) {
                $tel->has_been_sent = 1;
                $tel->save();
                $user = $UtelSupp->user;
                $name = $user->firstname . " " . $user->lastname;
                //to user
                $send = (new SMS())->sendVerification($user->mobile, "tel_24h_remained", "name=={$name}&date==" . $tel->day_tel_fa . "&clock_1==" . $tel->from_time .
                    "&clock_2==" . $tel->to_time);
                $send = User::sendMail(new MailVerificationCode("tel_24h_remained", [
                    $name,
                    $tel->day_tel_fa,
                    $tel->from_time,
                    $tel->to_time,
                ], "tel_24h_remained"), $user->email);
                $notif = (new Notification("tel_24h_remained", [
                    $name,
                    $tel->day_tel_fa,
                    $tel->from_time,
                    $tel->to_time,
                ]))->send($user->id);

                //to  expert
                $sups = UserSupervisor::where('supervisor_id', '<>', 26)->where('user_id', $user->id)->get();
                foreach ($sups as $sup) {
                    if ($sup->supervisor->level === 5 || $sup->supervisor->level === 7) {
                        $notif = (new Notification("expert_tel_24h_remained", [
                            $sup->supervisor->firstname,
                            $tel->day_tel_fa,
                            $tel->from_time,
                            $tel->to_time,
                        ]))->send($sup->supervisor->id);

                        $send = User::sendMail(new MailVerificationCode("expert_tel_24h_remained", [
                            $sup->supervisor->firstname,
                            $tel->day_tel_fa,
                            $tel->from_time,
                            $tel->to_time,
                        ], "expert_tel_24h_remained"), $sup->supervisor->email);
                    }
                }
            }
        }
    }
}
