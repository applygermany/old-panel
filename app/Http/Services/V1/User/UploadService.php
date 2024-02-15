<?php

namespace App\Http\Services\V1\User;

use App\Models\Motivation;
use App\Models\Pricing;
use App\Models\Resume;
use App\Models\User;
use App\Models\Upload;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use Illuminate\Http\Request;
use App\Providers\Notification;
use App\Mail\MailVerificationCode;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Calculation\Financial\Securities\Price;

class UploadService
{
    public function uploadMandatoryFile(Request $request)
    {
        $title = 'تمامی مدارک دبیرستان';
        if ($request->type == 2) {
            $title = 'گواهی قبولی کنکور';
        }
        if ($request->type == 3) {
            $title = 'مدرک زبان';
        }
        if ($request->type == 4) {
            $title = 'پاسپورت';
        }
        if ($request->type == 5) {
            $title = 'گواهی کار';
        }
        if ($request->type == 6) {
            $title = 'عکس پرسنلی';
        }
        if ($request->type == 7) {
            $title = 'قرارداد';
        }
        if ($request->type == 8) {
            $title = 'تمامی مدارک دوره کارشناسی';
        }
        if ($request->type == 9) {
            $title = 'توصیه نامه';
        }
        if ($request->type == 10) {
            $title = 'گواهی شرکت در دوره های تخصصی';
        }
        if ($request->type == 11) {
            $title = 'گواهی شرح دروس';
        }
        if ($request->type == 12) {
            $title = 'گواهی سیستم نمره دهی';
        }
        if ($request->type == 13) {
            $title = 'سایر مدارک';
        }
        $upload = new Upload();
        $upload->user_id = auth()->guard('api')->id();
        $upload->title = $title;
        $upload->date = MyHelpers::numberToEnglish(JDF::jdate('Y/m/d'));
        $upload->type = $request->type;
        if ($request->text) {
            $upload->text = $request->text;
        }

        if ($upload->save()) {
            if ($request->file('file')) {
                $ext = '.pdf';
                if ($request->type == 6) {
                    $ext = '.jpg';
                }
                $folder = '/uploads/madarek/';
                $file = $request->file('file');
                $file->move(public_path() . $folder, $upload->id . $ext);
            }
            if ($request->type == 7) {
                $expert_id = 0;
                $experts = auth()->guard('api')->user()->supervisor()->get();
                foreach ($experts as $expert) {
                    $e = $expert->supervisor;
                    if ($e->id == 26) {
                        $expert_id = $e->id;

                        $user = auth()->guard('api')->user();
                        $notif = (new Notification("expert_contract", [$user->firstname . " " . $user->lastname]))
                            ->send($expert_id);
                        $send = User::sendMail(new MailVerificationCode("expert_contract", [$user->firstname . " " . $user->lastname], "expert_contract"), $e->email);

                        break;
                    }
                }

                //Send Email to zeynab
                $user = auth()->guard('api')->user();
                $admin = User::find(19895);
                $send = User::sendMail(new MailVerificationCode("expert_contract", [$user->firstname . " " . $user->lastname], "expert_contract"), $admin->email);

            } else {
                $expert_id = 0;
                $experts = auth()->guard('api')->user()->supervisor()->get();
                foreach ($experts as $expert) {
                    $e = $expert->supervisor;
                    if ($e->id !== 26) {
                        $expert_id = $e->id;
                        $user = auth()->guard('api')->user();
                        (new Notification("expert_upload", [
                            $user->firstname . " " . $user->lastname,
                            $title,
                        ]))->send($expert_id);

                        $mail = User::sendMail(new MailVerificationCode("expert_upload", [
                            $user->firstname . " " . $user->lastname,
                            $title,
                        ], "expert_upload"), $e->email);
                    }
                }
            }
            return 1;
        }
        return 0;
    }

    public function uploadFile(Request $request)
    {
        $upload = new Upload();
        $upload->user_id = auth()->guard('api')->id();
        $upload->title = $request->title;
        if ($request->text) {
            $upload->text = $request->text;
        }
        $upload->date = MyHelpers::numberToEnglish(JDF::jdate('Y/m/d'));
        if ($upload->save()) {
            if ($request->file('file')) {
                $folder = '/uploads/madarek/';
                $file = $request->file('file');
                $file->move(public_path() . $folder, $upload->id . '.pdf');
            }
            return 1;
        }
        return 0;
    }

    public function deleteUpload(Request $request)
    {
        $upload = auth()->guard('api')->user()->uploads()->find($request->id);
        if ($upload and $upload['status']==0) {
            if ($upload->type != 6) {
                if (is_file(public_path('uploads/madarek/' . $upload->id . '.pdf'))) {
                    unlink(public_path('uploads/madarek/' . $upload->id . '.pdf'));
                } elseif (is_file(public_path('uploads/madarek/' . $upload->id . '.jpg'))) {
                    unlink(public_path('uploads/madarek/' . $upload->id . '.jpg'));
                }
            }
            $upload->delete();
            return 1;
        }elseif ($upload['status']==0){
            return 2;//file taiid  shode ast va emkane hazf nist
        }
        return 0;
    }
    public function uploads()
    {
        $acceptance = auth()->guard('api')->user()->acceptances()->first();
        if (!$acceptance) {
            return 0;
        }
        $admittanceFor=$acceptance->admittance;
        $uploadArray = [];
        $mandatoryUploadArray = [];
        $mandatoryUploadedArray = [];
        $loop = 13;
        $exclude = [
            8,
        ];
        if ($acceptance->admittance == 'ارشد' || $acceptance->admittance == 'دکترا') {
            $exclude = [
            ];
        }
        if ($acceptance->admittance == 'لیسانس') {
            $exclude = [
            ];
        }
        $contract = 0;
        $uploadTitles = [];
        for ($i = 1; $i <= $loop; $i++) {
            $ext = '.pdf';
            $mandatory = 0;
            $type = 1;
            $title = '';
            $text = '';
            if (!in_array($i, $exclude)) {
                if ($i == 1) {
                    $mandatory = 1;
                    $title = 'تمامی مدارک دبیرستان';
                }
                if ($i == 2) {
                    $title = 'گواهی قبولی کنکور';
                }
                if ($i == 3) {
                    $mandatory = 1;
                    $title = 'مدرک زبان';
                }
                if ($i == 4) {
                    $mandatory = 1;
                    $title = 'پاسپورت';
                }
                if ($i == 5) {
                    $title = 'گواهی کار';
                }
                if ($i == 6) {
                    $mandatory = 1;
                    $ext = '.jpg';
                    $title = 'عکس پرسنلی';
                }
                if ($i == 7) {
                    $mandatory = 1;
                    $title = 'قرارداد';
                    $type = 2;
                    $text = Upload::where('user_id', auth()->guard('api')->id())->where('type', 7)->first()->text;
                }
                if ($i == 8) {
                    $mandatory = 1;
                    $title = 'تمامی مدارک دوره کارشناسی';
                }
                if ($i == 9) {
                    $title = 'توصیه نامه';
                }
                if ($i == 10) {
                    $title = 'گواهی شرکت در دوره های تخصصی';
                }
                if ($i == 11) {
                    $title = 'گواهی شرح دروس';
                }
                if ($i == 12) {
                    $title = 'گواهی سیستم نمره دهی';
                }
                if ($i == 13) {
                    $title = 'سایر مدارک';
                }
                $upload = auth()->guard('api')->user()->uploads()->where('type', $i)->first();
                if ($upload) {
                    if ($i == 6) {
                        $ext = '.jpg';
                    }
                    if (is_file(public_path('uploads/madarek/' . $upload->id . $ext))) {
                        if ($i == 7) {
                            $contract = $upload->id;
                        }
                        $file = public_path('uploads/madarek/' . $upload->id . $ext);
                        $url = route('madrak', ['id' => $upload->id]);
                        $size = MyHelpers::formatSizeUnits(filesize($file));
                        $mandatoryUploadArray[] = [
                            'id' => $upload->id,
                            'status' => $upload->status,
                            'title' => $title,
                            'text' => $text,
                            'date' => $upload->date,
                            'url' => $url,
                            'size' => $size,
                            'mandatory' => $mandatory,
                            'type' => $type,
                        ];
                        $mandatoryUploadedArray[] = $i;
                    }
                }
                $uploadTitles[] = ['title' => $title, 'ext' => $ext, 'type' => $i, "mandatory" => $mandatory];
            }
        }
        $addUploads=$upload = auth()->guard('api')->user()->uploads()->get();
        foreach ($addUploads as $add){
            $check=0;
            foreach ($mandatoryUploadArray as $mandatory){
                if($mandatory['id'] == $add['id']){
                    $check=1;
                }
            }
            if($check==0){
                $file = public_path('uploads/madarek/' . $add->id . $ext);
                $url = route('madrak', ['id' => $add->id]);
                $size = MyHelpers::formatSizeUnits(filesize($file));
                $customArray=[
                    'id' => $add->id,
                    'status' => $add->status,
                    'title' => $add->title,
                    'text' => $add->text,
                    'date' => $add->date,
                    'url' => $url,
                    'size' => $size,
                    'mandatory' => 1,
                    'type' => 1,
                ];
                array_push($mandatoryUploadArray,$customArray);
            }
        }
        usort($mandatoryUploadArray, [$this, "cmp"]);
        $uploads = auth()->guard('api')->user()->uploads()->where('type', 0)->get();
        $i = 0;
        foreach ($uploads as $upload) {
            if (is_file(public_path('uploads/madarek/' . $upload->id . '.pdf'))) {
                $i++;
                $file = public_path('uploads/madarek/' . $upload->id . '.pdf');
                $url = route('madrak', ['id' => $upload->id]);
                $size = MyHelpers::formatSizeUnits(filesize($file));
                $uploadArray[] = [
                    'id' => $upload->id,
                    'title' => $upload->title,
                    'date' => $upload->date,
                    'url' => $url,
                    'size' => $size,
                ];
                $mandatoryUploadArray[] = [
                    'id' => $upload->id,
                    'title' => $upload->text,
                    'date' => $upload->date,
                    'url' => $url,
                    'size' => $size,
                    'mandatory' => 0,
                    'type' => 2,
                ];
                $mandatoryUploadedArray[] = $i;
            }
        }
        $user=auth()->guard('api')->user();
        $checkMotive=Motivation::where('user_id',$user->id)->where('url_uploaded_from_user','<>',null)->latest()->first();
        if(isset($checkMotive)){
            $checkMotive->url_uploaded_from_user=json_decode($checkMotive->url_uploaded_from_user, true);
            foreach($checkMotive['url_uploaded_from_user'] as $userFile){
                $fakeArray= [
                    'id' => $checkMotive->id,
                    'title' => 'انگیزه نامه',
                    'date' => '',
                    'url' => $userFile,
                    'size' => '',
                    'text' => '',
                    'status'=>10,
                    'mandatory' => 1,
                    'type' => 2,
                ];
                array_push($mandatoryUploadArray,$fakeArray);
            }
        }
        $checkResume=Resume::where('user_id',$user->id)->where('url_uploaded_from_user','<>',null)->latest()->first();
        if(isset($checkResume)){
            $checkResume->url_uploaded_from_user= json_decode($checkResume->url_uploaded_from_user, true);
            foreach($checkResume['url_uploaded_from_user'] as $userFile){
                $fakeArray= [
                    'id' => $checkMotive->id,
                    'title' => 'انگیزه نامه',
                    'date' => '',
                    'url' => $userFile,
                    'size' => '',
                    'status'=>10,
                    'text' => '',
                    'mandatory' => 1,
                    'type' => 2,
                ];
                array_push($mandatoryUploadArray,$fakeArray);
            }
        }

        return [$mandatoryUploadArray, $mandatoryUploadedArray, $uploadArray, $loop, $contract, $uploadTitles,$admittanceFor];
    }
    public function uploadsTwo()
    {
        $acceptance = auth()->guard('api')->user()->acceptances()->first();
        if (!$acceptance) {
            return 0;
        }
        $uploadArray = [];
        $mandatoryUploadArray = [];
        $mandatoryUploadedArray = [];
        $loop = 13;
        $exclude = [
            8,
        ];
        if ($acceptance->admittance == 'ارشد' || $acceptance->admittance == 'دکترا') {
            $exclude = [
            ];
        }
        if ($acceptance->admittance == 'لیسانس') {
            $exclude = [
            ];
        }
        $contract = 0;
        $uploadTitles = [];
        for ($i = 1; $i <= $loop; $i++) {
            $ext = '.pdf';
            $mandatory = 0;
            $type = 1;
            $title = '';
            $text = '';
            if (!in_array($i, $exclude)) {
                if ($i == 1) {
                    $mandatory = 1;
                    $title = 'تمامی مدارک دبیرستان';
                }
                if ($i == 2) {
                    $title = 'گواهی قبولی کنکور';
                }
                if ($i == 3) {
                    $mandatory = 1;
                    $title = 'مدرک زبان';
                }
                if ($i == 4) {
                    $mandatory = 1;
                    $title = 'پاسپورت';
                }
                if ($i == 5) {
                    $title = 'گواهی کار';
                }
                if ($i == 6) {
                    $mandatory = 1;
                    $ext = '.jpg';
                    $title = 'عکس پرسنلی';
                }
                if ($i == 7) {
                    $mandatory = 1;
                    $title = 'قرارداد';
                    $type = 2;
                    $text = Upload::where('user_id', auth()->guard('api')->id())->where('type', 7)->first()->text;
                }
                if ($i == 8) {
                    $mandatory = 1;
                    $title = 'تمامی مدارک دوره کارشناسی';
                }
                if ($i == 9) {
                    $title = 'توصیه نامه';
                }
                if ($i == 10) {
                    $title = 'گواهی شرکت در دوره های تخصصی';
                }
                if ($i == 11) {
                    $title = 'گواهی شرح دروس';
                }
                if ($i == 12) {
                    $title = 'گواهی سیستم نمره دهی';
                }
                if ($i == 13) {
                    $title = 'سایر مدارک';
                }
                $upload = auth()->guard('api')->user()->uploads()->where('type', $i)->first();
                if ($upload) {
                    if ($i == 6) {
                        $ext = '.jpg';
                    }
                    if (is_file(public_path('uploads/madarek/' . $upload->id . $ext))) {
                        if ($i == 7) {
                            $contract = $upload->id;
                        }
                        $file = public_path('uploads/madarek/' . $upload->id . $ext);
                        $url = route('madrak', ['id' => $upload->id]);
                        $size = MyHelpers::formatSizeUnits(filesize($file));
                        $mandatoryUploadArray[] = [
                            'id' => $upload->id,
                            'status' => $upload->status,
                            'title' => $title,
                            'text' => $text,
                            'date' => $upload->date,
                            'url' => $url,
                            'size' => $size,
                            'mandatory' => $mandatory,
                            'type' => $type,
                        ];
                        $mandatoryUploadedArray[] = $i;
                    }
                }
                $uploadTitles[] = ['title' => $title, 'ext' => $ext, 'type' => $i, "mandatory" => $mandatory];
            }
        }
        usort($mandatoryUploadArray, [$this, "cmp"]);
        $uploads = auth()->guard('api')->user()->uploads()->where('type', 0)->get();
        $i = 0;
        foreach ($uploads as $upload) {
            if (is_file(public_path('uploads/madarek/' . $upload->id . '.pdf'))) {
                $i++;
                $file = public_path('uploads/madarek/' . $upload->id . '.pdf');
                $url = route('madrak', ['id' => $upload->id]);
                $size = MyHelpers::formatSizeUnits(filesize($file));
                $uploadArray[] = [
                    'id' => $upload->id,
                    'title' => $upload->title,
                    'date' => $upload->date,
                    'url' => $url,
                    'size' => $size,
                ];
                $mandatoryUploadArray[] = [
                    'id' => $upload->id,
                    'title' => $upload->text,
                    'date' => $upload->date,
                    'url' => $url,
                    'size' => $size,
                    'mandatory' => 0,
                    'type' => 2,
                ];
                $mandatoryUploadedArray[] = $i;
            }
        }
        $user=auth()->guard('api')->user();
        $checkMotive=Motivation::where('user_id',$user->id)->where('url_uploaded_from_user','<>',null)->latest()->first();
        if(isset($checkMotive)){
            $checkMotive->url_uploaded_from_user=json_decode($checkMotive->url_uploaded_from_user, true);
            foreach($checkMotive['url_uploaded_from_user'] as $userFile){
                $fakeArray= [
                    'id' => $checkMotive->id,
                    'title' => 'انگیزه نامه',
                    'date' => '',
                    'url' => $userFile,
                    'size' => '',
                    'text' => '',
                    'status'=>10,
                    'mandatory' => 1,
                    'type' => 2,
                ];
                array_push($mandatoryUploadArray,$fakeArray);
            }
        }
        $checkResume=Resume::where('user_id',$user->id)->where('url_uploaded_from_user','<>',null)->latest()->first();
        if(isset($checkResume)){
            $checkResume->url_uploaded_from_user= json_decode($checkResume->url_uploaded_from_user, true);
            foreach($checkResume['url_uploaded_from_user'] as $userFile){
                $fakeArray= [
                    'id' => $checkMotive->id,
                    'title' => 'انگیزه نامه',
                    'date' => '',
                    'url' => $userFile,
                    'size' => '',
                    'status'=>10,
                    'text' => '',
                    'mandatory' => 1,
                    'type' => 2,
                ];
                array_push($mandatoryUploadArray,$fakeArray);
            }
        }

        return [$mandatoryUploadArray, $mandatoryUploadedArray, $uploadArray, $loop, $contract, $uploadTitles];
    }
    public function uploadsOldOne()
    {
        $acceptance = auth()->guard('api')->user()->acceptances()->first();
        if (!$acceptance) {
            return 0;
        }
        $uploadArray = [];
        $mandatoryUploadArray = [];
        $mandatoryUploadedArray = [];
        $loop = 13;
        $exclude = [
            8,
        ];
        if ($acceptance->admittance == 'ارشد' || $acceptance->admittance == 'دکترا') {
            $exclude = [
            ];
        }
        if ($acceptance->admittance == 'لیسانس') {
            $exclude = [
            ];
        }
        $contract = 0;
        $uploadTitles = [];
        for ($i = 1; $i <= $loop; $i++) {
            $ext = '.pdf';
            $mandatory = 0;
            $type = 1;
            $title = '';
            $text = '';
            if (!in_array($i, $exclude)) {
                if ($i == 1) {
                    $mandatory = 1;
                    $title = 'تمامی مدارک دبیرستان';
                }
                if ($i == 2) {
                    $title = 'گواهی قبولی کنکور';
                }
                if ($i == 3) {
                    $mandatory = 1;
                    $title = 'مدرک زبان';
                }
                if ($i == 4) {
                    $mandatory = 1;
                    $title = 'پاسپورت';
                }
                if ($i == 5) {
                    $title = 'گواهی کار';
                }
                if ($i == 6) {
                    $mandatory = 1;
                    $ext = '.jpg';
                    $title = 'عکس پرسنلی';
                }
                if ($i == 7) {
                    $mandatory = 1;
                    $title = 'قرارداد';
                    $type = 2;
                    $text = Upload::where('user_id', auth()->guard('api')->id())->where('type', 7)->first()->text;
                }
                if ($i == 8) {
                    $mandatory = 1;
                    $title = 'تمامی مدارک دوره کارشناسی';
                }
                if ($i == 9) {
                    $title = 'توصیه نامه';
                }
                if ($i == 10) {
                    $title = 'گواهی شرکت در دوره های تخصصی';
                }
                if ($i == 11) {
                    $title = 'گواهی شرح دروس';
                }
                if ($i == 12) {
                    $title = 'گواهی سیستم نمره دهی';
                }
                if ($i == 13) {
                    $title = 'سایر مدارک';
                }
                $upload = auth()->guard('api')->user()->uploads()->where('type', $i)->first();
                if ($upload) {
                    if ($i == 6) {
                        $ext = '.jpg';
                    }
                    if (is_file(public_path('uploads/madarek/' . $upload->id . $ext))) {
                        if ($i == 7) {
                            $contract = $upload->id;
                        }
                        $file = public_path('uploads/madarek/' . $upload->id . $ext);
                        $url = route('madrak', ['id' => $upload->id]);
                        $size = MyHelpers::formatSizeUnits(filesize($file));
                        $mandatoryUploadArray[] = [
                            'id' => $upload->id,
                            'status' => $upload->status,
                            'title' => $title,
                            'text' => $text,
                            'date' => $upload->date,
                            'url' => $url,
                            'size' => $size,
                            'mandatory' => $mandatory,
                            'type' => $type,
                        ];
                        $mandatoryUploadedArray[] = $i;
                    }
                }
                $uploadTitles[] = ['title' => $title, 'ext' => $ext, 'type' => $i, "mandatory" => $mandatory];
            }
        }
        usort($mandatoryUploadArray, [$this, "cmp"]);
        $uploads = auth()->guard('api')->user()->uploads()->where('type', 0)->get();
        $i = 0;
        foreach ($uploads as $upload) {
            if (is_file(public_path('uploads/madarek/' . $upload->id . '.pdf'))) {
                $i++;
                $file = public_path('uploads/madarek/' . $upload->id . '.pdf');
                $url = route('madrak', ['id' => $upload->id]);
                $size = MyHelpers::formatSizeUnits(filesize($file));
                $uploadArray[] = [
                    'id' => $upload->id,
                    'title' => $upload->title,
                    'date' => $upload->date,
                    'url' => $url,
                    'size' => $size,
                ];
                $mandatoryUploadArray[] = [
                    'id' => $upload->id,
                    'title' => $upload->text,
                    'date' => $upload->date,
                    'url' => $url,
                    'size' => $size,
                    'mandatory' => 0,
                    'type' => 2,
                ];
                $mandatoryUploadedArray[] = $i;
            }
        }
        return [$mandatoryUploadArray, $mandatoryUploadedArray, $uploadArray, $loop, $contract, $uploadTitles];
    }

    public function cmp($a, $b)
    {
        if ($a["type"] == $b["type"]) {
            return 0;
        }
        return ($a["type"] > $b["type"]) ? -1 : 1;
    }
}
