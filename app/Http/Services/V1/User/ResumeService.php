<?php

namespace App\Http\Services\V1\User;

use App\Models\ResumeMotivationIds;
use App\Models\Upload;
use App\Models\User;
use App\Models\Resume;
use App\Models\UserSupervisor;
use App\Providers\SMS;
use App\Models\Pricing;
use App\Models\ResumeWork;
use App\Models\ResumeHobby;
use App\Models\ResumeCourse;
use Illuminate\Http\Request;
use App\Models\ResumeLanguage;
use App\Models\ResumeResearch;
use App\Providers\Notification;
use App\Mail\MailVerificationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\ResumeEducationRecord;
use App\Models\ResumeSoftwareKnowledge;

class ResumeService
{
    public function resume()
    {
        $resume = Resume::where('user_id', auth()->guard('api')->id())->where("status", -1)->first();
        if (!$resume) {
            $resume = new Resume();
            $resume->user_id = auth()->guard('api')->id();
            $resume->language = NULL;
            $resume->status = -1;
            $resume->save();
        }
        return $resume;
    }

    public function resumeId($id)
    {
        $resume = Resume::where("id", $id)->first();
        return $resume;
    }

    public function removeResumeAfter12H()
    {
        $resumes = Resume::where("status", -1)->get();
        foreach ($resumes as $resume) {
            if (time() - strtotime($resume->created_at) >= 43200) {
                $resume->delete();
            }
        }
        return 0;
    }

    public function updateResumeInformation(Request $request)
    {
        $resume = auth()->guard('api')->user()->resumes()->where("id", $request->id)->first();
        if (!$resume) {
            return 0;
        }
        if ($request->resume['theme']) {
            $resume->theme = $request->resume['theme'];
        }
        if ($request->resume['language']) {
            $resume->language = $request->resume['language'];
        }
        if ($request->resume['name']) {
            $resume->name = $request->resume['name'];
        }
        if ($request->resume['family']) {
            $resume->family = $request->resume['family'];
        }
        if ($request->resume['birthDate']) {
            $resume->birth_date = $request->resume['birthDate'];
        }
        if ($request->resume['birthPlace']) {
            $resume->birth_place = $request->resume['birthPlace'];
        }
        if ($request->resume['phone']) {
            $resume->phone = $request->resume['phone'];
        }
        if ($request->resume['color']) {
            $resume->color = $request->resume['color'];
        }
        if ($request->resume['email']) {
            $resume->email = $request->resume['email'];
        }
        if ($request->resume['address']) {
            $resume->address = $request->resume['address'];
        }
        if ($request->resume['socialmediaLinks']) {
            $resume->socialmedia_links = $request->resume['socialmediaLinks'];
        }
        if ($request->resume['text']) {
            $resume->text = $request->resume['text'];
        }
        $saved = $resume->save();
        // $factor = Factor::where("factor_desc", "=", "رزومه #".$resume->id)->first();
        // if(!$factor){
        //     $factor = new Factor();
        //     $factor->user_id =  auth()->guard('api')->user()->id;
        //     $factor->status =  0;
        //     $factor->amount =  Pricing::find(1)->resume_price;
        //     $factor->amount_final =  Pricing::find(1)->resume_price;
        //     $factor->amount_euro =  0;
        //     $factor->factor_desc =  "رزومه #".$resume->id;
        //     if($factor->save()){
        //         return 1;
        //     }
        // }
        if ($saved) {

            return 1;
        }
        return 0;
    }

    public function resumes()
    {
        $resumes = Resume::where('user_id', auth()->guard('api')->id())->where("status", ">", 0)->get();
        foreach ($resumes as &$resume) {
            $resume->admin_attachment = explode(",", $resume->admin_attachment);
        }
        return $resumes;
    }

    public function updateResume(Request $request)
    {
        $resume = auth()->guard('api')->user()->resumes()->where("id", $request->id)->first();
        if (!$resume) {
            return 0;
        }
        $resume->theme = $request->resume['theme'];
        $resume->language = $request->resume['language'];
        $resume->name = $request->resume['name'];
        $resume->family = $request->resume['family'];
        $resume->birth_date = $request->resume['birthDate'];
        $resume->birth_place = $request->resume['birthPlace'];
        $resume->phone = $request->resume['phone'];
        $resume->color = $request->resume['color'];
        $resume->status = auth()->guard('api')->user()->type == 2 ? 1 : 0;
        $resume->email = $request->resume['email'];
        $resume->address = $request->resume['address'];
        $resume->socialmedia_links = $request->resume['socialmediaLinks'];
        $resume->text = $request->resume['text'];
        $saved = $resume->save();

        if ($saved) {

            $storeId = new ResumeMotivationIds();
            $storeId->model_id = $resume->id;
            $storeId->user_id = $resume->user_id;
            $storeId->model_type = 'resume';
            $storeId->save();

            if (auth()->guard('api')->user()->type == 2) {
                $name = $resume->user->firstname . " " . $resume->user->lastname;
                $send = (new SMS())->sendVerification($resume->user->mobile, "resume_added", "name=={$name}&order=={$resume->id}");
                $send = User::sendMail(new MailVerificationCode("resume_added", [
                    $name,
                    $resume->id,
                ], "resume_added"), $resume->user->email);
                $notif = (new Notification("resume_added", [$name, $resume->id]))->send($resume->user->id);
                //to admin

                $order_admin = User::where('level', 4)->where("admin_permissions", "like", '%"orders":1%')->get();
                foreach ($order_admin as $admin) {
                    $send = User::sendMail(new MailVerificationCode("admin_resume_added", [
                        $name,
                        $resume->id,
                    ], "admin_resume_added"), $admin->email);
                    $notif = (new Notification("admin_resume_added", [$name, $resume->id]))->send($admin->id);
                }

                $supps = UserSupervisor::where('user_id', $resume->user_id)->get();
                foreach ($supps as $sup) {
                    if ($sup->supervisor->level === 2) {
                        $send = User::sendMail(new MailVerificationCode("admin_resume_added", [
                            $name,
                            $resume->id,
                        ], "admin_motivation_added"), $sup->supervisor->email);
                        $notif = (new Notification("admin_resume_added", [$name, $resume->id]))->send($sup->supervisor->id);

                        break;
                    }
                }

                return [100, $resume->id];
            } else {
                $invoice = new InvoiceService();
                return [$invoice->goPay($resume->user_id, Pricing::find(1)->resume_price, 2, $resume->id), $resume->id];
            }
        }
        return 0;
    }

    public function uploadImage(Request $request)
    {
        if ($request->file('image')) {
            $folder = '/uploads/resumeImage/';
            $file = $request->file('image');
            $file->move(public_path() . $folder, $request->id . '.jpg');
        }
        return 1;
    }

    public function editResume(Request $request)
    {
        $resume = auth()->guard('api')->user()->resumes()->where('id', $request->id)->first();
        if (!$resume)
            return 0;
        if ($request->file('file')) {
            $folder = '/uploads/resumeUserFile/';
            $file = $request->file('file');
            $file->move(public_path() . $folder, $resume->id . '.pdf');
        }
        $resume->user_comment = $request->editRequestText;
        $resume->url_uploaded_from_user = route("resumeUserFile", ["id" => $resume->id]);
        $resume->status = 4;
        $resume->is_accepted = false;
        $resume->admin_accepted_filename = null;
        if ($resume->save()) {

            $upload = Upload::where('type', 12)->where('text', $resume->id)->delete();

            //to admin
            $name = $resume->user->firstname . " " . $resume->user->lastname;
            $order_admin = User::where('level', 4)->where("admin_permissions", "like", '%"orders":1%')->first();
            $send = User::sendMail(new MailVerificationCode("admin_resume_edit_needed", [
                $name,
                $resume->id,
            ], "admin_resume_edit_needed"), $order_admin->email);
            $notif = (new Notification("admin_resume_edit_needed", [$name, $resume->id]))->send($order_admin->id);
            return 1;
        }
        return 0;
    }


    public function addEducationRecord(Request $request)
    {
        $resume = auth()->guard('api')->user()->resumes()->where("id", $request->id)->first();
        $educationRecord = new ResumeEducationRecord();
        $educationRecord->resume_id = $resume->id;
        $educationRecord->grade = $request->grade;
        $educationRecord->from_date_year = $request->fromDateYear;
        $educationRecord->from_date_month = $request->fromDateMonth;
        $educationRecord->to_date_year = $request->toDateYear;
        $educationRecord->to_date_month = $request->toDateMonth;
        $educationRecord->school_name = $request->schoolName;
        $educationRecord->field = $request->field;
        $educationRecord->grade_score = $request->gradeScore;
        $educationRecord->city = $request->city;
        $educationRecord->text = $request->text;
        if ($educationRecord->save())
            return 1;
        return 0;
    }

    public function addEducationRecord_parsa($data)
    {
        $data = (object)$data;
        $resume = auth()->guard('api')->user()->resumes()->where("id", $data->id)->first();
        $educationRecord = new ResumeEducationRecord();
        $educationRecord->resume_id = $resume->id;
        $educationRecord->grade = $data->grade;
        $educationRecord->from_date_year = $data->fromDateYear;
        $educationRecord->from_date_month = $data->fromDateMonth;
        $educationRecord->to_date_year = $data->toDateYear;
        $educationRecord->to_date_month = $data->toDateMonth;
        $educationRecord->school_name = $data->schoolName;
        $educationRecord->field = $data->field;
        $educationRecord->grade_score = $data->gradeScore;
        $educationRecord->city = $data->city;
        $educationRecord->text = $data->text;
        if ($educationRecord->save())
            return 1;
        return 0;
    }

    public function deleteEducationRecord(Request $request)
    {
        $educationRecord = ResumeEducationRecord::find($request->_id);
        if ($educationRecord) {
            if ($educationRecord->delete())
                return 1;
        }
        return 0;
    }


    public function addLanguage(Request $request)
    {
        $resume = auth()->guard('api')->user()->resumes()->where("id", $request->id)->first();
        $language = new ResumeLanguage();
        $language->resume_id = $resume->id;
        $language->title = $request->title;
        $language->fluency_level = $request->fluencyLevel;
        $language->degree = $request->degree;
        $language->score = $request->score ? $request->score : 0;
        if ($language->save())
            return 1;
        return 0;
    }

    public function addLanguage_parsa($data)
    {
        $data = (object)$data;
        $resume = auth()->guard('api')->user()->resumes()->where("id", $data->id)->first();
        $language = new ResumeLanguage();
        $language->resume_id = $resume->id;
        $language->title = $data->title;
        $language->fluency_level = $data->fluencyLevel;
        $language->degree = $data->degree;
        $language->score = $data->score ? $data->Score : 0;
        if ($language->save())
            return 1;
        return 0;
    }

    public function deleteLanguage(Request $request)
    {

        $language = ResumeLanguage::find($request->_id);
        if ($language) {
            if ($language->delete()) {
                return 1;
            }
        }
        return 0;
    }


    public function addWork(Request $request)
    {
        $resume = auth()->guard('api')->user()->resumes()->where("id", $request->id)->first();
        $work = new ResumeWork();
        $work->resume_id = $resume->id;
        $work->from_date_year = $request->fromDateYear;
        $work->from_date_month = $request->fromDateMonth;
        $work->to_date_year = $request->toDateYear;
        $work->to_date_month = $request->toDateMonth;
        $work->company_name = $request->companyName;
        $work->position = $request->position;
        $work->city = $request->city;
        $work->text = $request->text;
        if ($work->save())
            return 1;
        return 0;
    }

    public function addWork_parsa($data)
    {
        $data = (object)$data;
        $resume = auth()->guard('api')->user()->resumes()->where("id", $data->id)->first();
        $work = new ResumeWork();
        $work->resume_id = $resume->id;
        $work->from_date_year = $data->fromDateYear;
        $work->from_date_month = $data->fromDateMonth;
        $work->to_date_year = $data->toDateYear;
        $work->to_date_month = $data->toDateMonth;
        $work->company_name = $data->companyName;
        $work->position = $data->position;
        $work->city = $data->city;
        $work->text = $data->text;
        if ($work->save())
            return 1;
        return 0;
    }

    public function deleteWork(Request $request)
    {

        $work = ResumeWork::find($request->_id);
        if ($work) {
            if ($work->delete())
                return 1;
        }
        return 0;
    }


    public function addSoftwareKnowledge(Request $request)
    {
        $resume = auth()->guard('api')->user()->resumes()->where("id", $request->id)->first();
        $softwareKnowledge = new ResumeSoftwareKnowledge();
        $softwareKnowledge->resume_id = $resume->id;
        $softwareKnowledge->title = $request->title;
        $softwareKnowledge->fluency_level = $request->fluencyLevel;
        if ($softwareKnowledge->save())
            return 1;
        return 0;
    }

    public function addSoftwareKnowledge_parsa($data)
    {
        $data = (object)$data;
        $resume = auth()->guard('api')->user()->resumes()->where("id", $data->id)->first();
        $softwareKnowledge = new ResumeSoftwareKnowledge();
        $softwareKnowledge->resume_id = $resume->id;
        $softwareKnowledge->title = $data->title;
        $softwareKnowledge->fluency_level = $data->fluencyLevel;
        if ($softwareKnowledge->save())
            return 1;
        return 0;
    }

    public function deleteSoftwareKnowledge(Request $request)
    {
        $softwareKnowledge = ResumeSoftwareKnowledge::find($request->_id);
        if ($softwareKnowledge) {
            if ($softwareKnowledge->delete())
                return 1;
        }
        return 0;
    }


    public function addCourse(Request $request)
    {
        $resume = auth()->guard('api')->user()->resumes()->where("id", $request->id)->first();
        $course = new ResumeCourse();
        $course->resume_id = $resume->id;
        $course->title = $request->title;
        $course->organizer = $request->organizer;
        $course->year = $request->year;
        if ($course->save())
            return 1;
        return 0;
    }

    public function addCourse_parsa($data)
    {
        $data = (object)$data;
        $resume = auth()->guard('api')->user()->resumes()->where("id", $data->id)->first();
        $course = new ResumeCourse();
        $course->resume_id = $resume->id;
        $course->title = $data->courseTitle;
        $course->organizer = $data->courseOrganizer;
        $course->year = $data->courseYear;
        if ($course->save())
            return 1;
        return 0;
    }

    public function deleteCourse(Request $request)
    {

        $course = ResumeCourse::find($request->_id);
        if ($course) {
            if ($course->delete())
                return 1;
        }
        return 0;
    }

    public function addResearch(Request $request)
    {
        $resume = auth()->guard('api')->user()->resumes()->where("id", $request->id)->first();
        $research = new ResumeResearch();
        $research->resume_id = $resume->id;
        $research->type = $request->type;
        $research->title = $request->title;
        $research->year = $request->year;
        $research->text = $request->text;
        if ($research->save())
            return 1;
        return 0;
    }

    public function addResearch_parsa($data)
    {
        $data = (object)$data;
        $resume = auth()->guard('api')->user()->resumes()->where("id", $data->id)->first();
        $research = new ResumeResearch();
        $research->resume_id = $resume->id;
        $research->type = $data->type;
        $research->title = $data->title;
        $research->year = $data->year;
        $research->text = $data->text;
        if ($research->save())
            return 1;
        return 0;
    }

    public function deleteResearch(Request $request)
    {
        $research = ResumeResearch::find($request->_id);
        if ($research) {
            if ($research->delete())
                return 1;
        }
        return 0;
    }

    public function addHobby($data)
    {
        $data = (object)$data;
        $resume = auth()->guard('api')->user()->resumes()->where("id", $data->id)->first();
        $hobby = new ResumeHobby();
        $hobby->resume_id = $resume->id;
        $hobby->title = $data->title;
        if ($hobby->save())
            return 1;
        return 0;
    }

    public function deleteHobby(Request $request)
    {

        $hobby = ResumeHobby::find($request->_id);
        if ($hobby) {
            if ($hobby->delete())
                return 1;
        }
        return 0;
    }


    public function deletePDF(Request $request)
    {

        $file = 'uploads/resumeUserFile/' . $request->id . '.pdf';
        if (!unlink(public_path($file))) {
            return 0;
        }
        $resume = auth()->guard('api')->user()->resumes()->where("id", $request->id)->first();
        $resume->url_uploaded_from_user = "";
        $resume->save();
        return 1;
    }

    public function updateResumeExtra(Request $request)
    {
        $resume = auth()->guard('api')->user()->resumes()->where('id', $request->id)->first();
        if ($resume) {
            $resume->user_comment = $request->extraText;
            $resume->status = 4;
            $resume->is_accepted = false;
            $resume->admin_accepted_filename = null;
            if ($resume->save()) {

                $upload = Upload::where('type', 12)->where('text', $resume->id)->delete();

                return 1;
            } else
                return 0;
        }
        return 0;
    }

    public function uploadPDF(Request $request)
    {

        if ($request->file('file')) {
            $folder = '/uploads/resumeUserFile/';
            $file = $request->file('file');
            $file->move(public_path() . $folder, $request->id . '.pdf');
            $resume = auth()->guard('api')->user()->resumes()->where("id", $request->id)->first();
            $resume->url_uploaded_from_user = route('resumeUserFile', ["id" => $request->id]);
            $resume->save();
        }
        return 1;
    }

    public function universities()
    {
        $select = "select `nag_user_universities`.`user_id`,
`nag_user_universities`.`id`,
`nag_user_universities`.`university_id`,
`nag_user_universities`.`field`,
`nag_user_universities`.chance_getting,
`nag_user_universities`.`description`,
`nag_user_universities`.`offer`,
`nag_user_universities`.`link`,
`nag_user_universities`.`status`,
`nag_user_universities`.`level_status`,
`nag_universities`.`title`,
`nag_universities`.`city`,
`nag_universities`.`state`,
`nag_universities`.`geographical_location`,
`nag_universities`.`city_crowd`,
`nag_universities`.`cost_living`,
`nag_universities`.`updated_at`
from `nag_user_universities`";
        $select .= " inner join `nag_users` on `nag_user_universities`.`user_id` = `nag_users`.`id`";
        $select .= " inner join `nag_universities` on `nag_user_universities`.`university_id` = `nag_universities`.`id`";
        $select .= " where `nag_user_universities`.`user_id` = " . auth()->guard('api')->id();
        $select .= " and `nag_user_universities`.`status` = 1";
        $select .= ' order by nag_user_universities.id desc';
        $universities = DB::select($select);
        return count($universities) > 0;
    }
}
