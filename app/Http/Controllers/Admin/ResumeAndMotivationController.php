<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\ResumeMotivationIds;
use App\Models\User;
use App\Models\Resume;
use App\Models\UserSupervisor;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use App\Providers\SMS;
use App\Models\Motivation;
use App\Models\ResumeWork;
use App\Models\ResumeHobby;
use App\Models\ResumeCourse;
use Illuminate\Http\Request;
use App\Exports\ResumeExport;
use App\Models\ResumeLanguage;
use App\Models\ResumeResearch;
use App\Providers\Notification;
use App\Exports\MotivationExport;
use App\Mail\MailVerificationCode;
use Illuminate\Routing\Controller;
use App\Models\MotivationUniversity;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ResumeEducationRecord;
use App\Models\ResumeSoftwareKnowledge;
use PDF;

class ResumeAndMotivationController extends Controller
{
    public function resumes()
    {
        $resumes = Resume::orderBy('updated_at', 'DESC')->where("status", ">", "-1")->paginate(10);
        $users = User::select('id', 'firstname', 'lastname', 'mobile', 'level')->get();
        $categories = Category::all();
        return view('admin.resumes.resumes', compact('resumes', 'users', 'categories'));
    }

    public function uploadResumeFromAdmin(Request $request)
    {
        $rules = [
            'id' => 'required',
            'resume' => 'required|mimes:zip,pdf,rar|max:100000',
        ];
        $customMessages = [
            'resume.required' => 'فایل را انتخاب کنید',
            'resume.mimes' => 'پسوند فایل معتبر نیست',
            'resume.max' => 'حجم فایل باید کمتر از 100 مگابایت باشد',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            session()->flash("warning", "خطا در فایل ورودی");
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($request->file('resume')) {
            $folder = '/uploads/resumeAdminFile/';
            $file = $request->file('resume');
            $file->move(public_path() . $folder, $request->id . '.' . $file->getClientOriginalExtension());
            $resume = Resume::find($request->id);
            $resume->url_uploaded_from_admin = route('resumeMainFile', ["id" => $request->id . '.' . $file->getClientOriginalExtension()]);
            $resume->admin_accepted_filename = route('resumeMainFile', ["id" => $request->id . '.' . $file->getClientOriginalExtension()]);
            $resume->is_accepted = 0;
            $resume->status = 2;
            if ($resume->save()) {
                $name = $resume->user->firstname . ' ' . $resume->user->lastname;
                $sups = UserSupervisor::where('user_id', $resume->user->id)
                    ->whereNotIn('supervisor_id', [26, $resume->writer_id])->get();
                foreach ($sups as $sup) {
                    if ($sup->supervisor->level === 5 || $sup->supervisor->level === 2) {
                        $send = User::sendMail(new MailVerificationCode("admin_file_upload_accept", [
                            'رزومه',
                            $name,
                        ], "admin_file_upload_accept"), $sup->supervisor->email);
                        $notif = (new Notification("admin_file_upload_accept", [
                            'رزومه',
                            $name,
                        ]))->send($sup->supervisor->id);
                    }
                }

                $send = User::sendMail(new MailVerificationCode("admin_file_upload_accept", [
                    'رزومه',
                    $name,
                ], "admin_file_upload_accept"), $resume->writer->email);
            }
        }
        session()->flash("success", "با موفقیت آپلود شد");
        return redirect()->back();
    }

    public function uploadMotivationFromAdmin(Request $request)
    {
        $rules = [
            'id' => 'required',
            'motivation' => 'required|mimes:zip,pdf,rar|max:100000',
        ];
        $customMessages = [
            'motivation.required' => 'فایل را انتخاب کنید',
            'motivation.mimes' => 'پسوند فایل معتبر نیست',
            'motivation.max' => 'حجم فایل باید کمتر از 100 مگابایت باشد',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            session()->flash("error", "خطا در فایل ورودی");
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if ($request->file('motivation')) {
            $folder = '/uploads/motivationAdminFile/';
            $file = $request->file('motivation');
            $fileName = $this->getFileName(public_path() . $folder, $request->id, 0, $file->getClientOriginalExtension());
            $file->move(public_path() . $folder, $fileName);
            $motivation = Motivation::find($request->id);
            $admins = json_decode($motivation->url_uploaded_from_admin, true);
            if (!is_array($admins)) {
                $admins = [];
            }
            $admins[] = route('motivationMainFile', ["id" => $fileName]);
            $motivation->url_uploaded_from_admin = json_encode($admins);
            $motivation->admin_accepted_filename = route('motivationMainFile', ["id" => $fileName]);
            $motivation->status = 2;
            $motivation->is_accepted = 0;
            if ($motivation->save()) {
                $name = $motivation->user->firstname . ' ' . $motivation->user->lastname;
                $sups = UserSupervisor::where('user_id', $motivation->user->id)
                    ->whereNotIn('supervisor_id', [26, $motivation->writer_id])->get();
                foreach ($sups as $sup) {
                    if ($sup->supervisor->level === 5 || $sup->supervisor->level === 2) {
                        $send = User::sendMail(new MailVerificationCode("admin_file_upload_accept", [
                            'انگیزه نامه',
                            $name,
                        ], "admin_file_upload_accept"), $sup->supervisor->email);
                        $notif = (new Notification("admin_file_upload_accept", [
                            'انگیزه نامه',
                            $name,
                        ]))->send($sup->supervisor->id);
                    }
                }

                $send = User::sendMail(new MailVerificationCode("admin_file_upload_accept", [
                    'انگیزه نامه',
                    $name,
                ], "admin_file_upload_accept"), $motivation->writer->email);
            }
        }
        session()->flash("success", "با موفقیت آپلود شد");
        return redirect()->back();
    }

    private function getFileName($string, $id, $index = 0, $exc)
    {
        if (is_file($string . $id . "_" . $index . '.' . $exc)) {
            return $this->getFileName($string, $id, $index + 1, '.' . $exc);
        }
        return $id . "_" . $index . '.pdf';
    }

    public function getResumes(Request $request)
    {
        $resume = Resume::Query()->where("status", ">", "-1");
        if ($request->searchUser) {
            $resume->where('user_id', $request->searchUser);
        }
        if ($request->searchId) {
            $resume->where('id', $request->searchId);
        }
        if ($request->searchTerm) {
            $users = User::where('category_id', $request->searchTerm)->pluck('id');
            $resume->whereIn('user_id', $users);
        }
        if ($request->searchWriter) {
            $resume->where('writer_id', $request->searchWriter);
        }
        if ($request->searchStartDate) {
            $date = MyHelpers::numberToEnglish(explode('/', $request->searchStartDate));
            $year = $date[0];
            $month = $date[1];
            $day = $date[2];
            $date = JDF::jalali_to_gregorian($year, $month, $day, '-');
            $resume->where('created_at', '>=', $date);
        }
        if ($request->searchEndDate) {
            $date = MyHelpers::numberToEnglish(explode('/', $request->searchEndDate));
            $year = $date[0];
            $month = $date[1];
            $day = $date[2];
            $date = JDF::jalali_to_gregorian($year, $month, $day, '-');

            $resume->where('created_at', '<=', $date);
        }
        $resumes = $resume->orderBy('updated_at', 'DESC')->paginate(10);
        $categories = Category::all();

        return view('admin.resumes.list', compact('resumes', 'categories'))->render();
    }

    public function deleteResume($id)
    {
        $resume = Resume::find($id);
        ResumeMotivationIds::where('model_id', $id)->delete();
        if ($resume->delete()) {
            session()->flash('success', 'رزومه حذف شد');
        } else {
            session()->flash('error', 'خطا در حذف رزومه');
        }
        return redirect()->back();
    }

    public function showResume($id)
    {
        $resume = Resume::find($id);
        $resume->url_uploaded_from_writer = json_decode($resume->url_uploaded_from_writer);
        $writers = User::where('level', 6)->get();
        return view('admin.resumes.show', compact('resume', 'writers'));
    }

    function addWriterToResume(Request $request, $id)
    {
        if ($request->writer) {
            $resume = Resume::find($id);
            $resume->writer_id = $request->writer;

            if (UserSupervisor::where('user_id', $resume->user_id)
                    ->where('supervisor_id', $request->writer)
                    ->count() == 0) {
                $sup = new UserSupervisor();
                $sup->user_id = $resume->user_id;
                $sup->supervisor_id = $request->writer;
                $sup->save();
            }
            $writer=User::where('id',$request->writer)->select('id','email')->first();
            if ($resume->save()) {
                //Send notification and email
                $mail = User::sendMail(new MailVerificationCode("add_writer", [
                    $resume->user->firstname . " " . $resume->user->lastname,
                    'رزومه'
                ], "add_writer"), $writer->email);
                $notif = (new Notification("add_writer", [
                    $resume->user->firstname . " " . $resume->user->lastname,
                    'رزومه'
                ]))->send($writer->id);


                session()->flash('success', 'نگارنده با موفقیت ثبت گردید');
                return redirect()->back();
            } else {
                session()->flash('error', 'ثبت با شکست مواجه گردید');
                return redirect()->back();
            }
        } else {
            session()->flash('error', 'نگارنده را انتخاب نمایید.');
            return redirect()->back();
        }
    }

    public function editResume(Request $request, $id)
    {
        $step = $request->step;
        //educationRecords = 0,lang=1, works=2, software=3, courses =4
        if ($step == 0) {
            return $this->editResumeEducationRecords($id);
        } elseif ($step == 1) {
            return $this->editResumeLanguage($id);
        } elseif ($step == 2) {
            return $this->editResumeWork($id);
        } elseif ($step == 3) {
            return $this->editResumeSoftwareKnowledge($id);
        } elseif ($step == 4) {
            return $this->editResumeCourses($id);
        } elseif ($step == 5) {
            return $this->editResumeResearch($id);
        } elseif ($step == 6) {
            return $this->editResumeHobby($id);
        } elseif ($step == 0) {
            return $this->editResumeDetails($id);
        } elseif ($step == 9) {
            return $this->editRequest($id);
        }
    }

    public function editResumeEducationRecords($id)
    {
        $resume = Resume::where("id", $id)->with("educationRecords")->first();
        $records = "";
        $educationRecords = $resume->educationRecords;
        //print_r($educationRecords);
        foreach ($educationRecords as $rec) {
            $records .= '<div class="form-group float-label col-12 col-lg-2">
<input name="step" type="hidden" id="step" value="0">
            <label for="editGrade" class="header-label">مقطع</label>
            <input name="editGrade-' . $rec->id . '" type="text" class="form-control" id="editGrade" value="' . $rec->grade . '">
        </div>

        <div class="form-group float-label col-12 col-lg-2">
            <label for="editField" class="header-label">رشته</label>
            <input name="editField-' . $rec->id . '" type="text" class="form-control" id="editField" value="' . $rec->field . '">
        </div>
        <div class="form-group float-label col-12 col-lg-2">
            <label for="editScore" class="header-label">معدل</label>
            <input name="editScore-' . $rec->id . '" type="text" class="form-control" id="editScore" value="' . $rec->grade_score . '">
        </div>
        <div class="form-group float-label col-12 col-lg-2">
            <label for="editCity" class="header-label">شهر</label>
            <input name="editCity-' . $rec->id . '" type="text" class="form-control" id="editCity" value="' . $rec->city . '">
        </div>
        <div class="form-group float-label col-12 col-lg-2">
            <label for="editFromDateYear" class="header-label">از تاریخ سال</label>
            <input name="editFromDateYear-' . $rec->id . '" type="text" class="form-control" id="editFromDateYear" value="' . $rec->from_date_year . '">
        </div>
          <div class="form-group float-label col-12 col-lg-2">
            <label for="editFromDateMonth" class="header-label">از تاریخ ماه</label>
            <input name="editFromDateMonth-' . $rec->id . '" type="text" class="form-control" id="editFromDateMonth" value="' . $rec->from_date_month . '">
        </div>
        <div class="form-group float-label col-12 col-lg-2">
            <label for="editToDateYear" class="header-label">تا تاریخ سال</label>
            <input name="editToDateYear-' . $rec->id . '" type="text" class="form-control" id="editToDateYear" value="' . $rec->to_date_year . '">
        </div>
         <div class="form-group float-label col-12 col-lg-2">
            <label for="editToDateMonth" class="header-label">تا تاریخ ماه</label>
            <input name="editToDateMonth-' . $rec->id . '" type="text" class="form-control" id="editToDateMonth" value="' . $rec->to_date_month . '">
        </div>
        <div class="form-group float-label col-12 col-lg-3">
            <label for="editSchoolName" class="header-label">مدرسه یا دانشگاه</label>
            <input name="editSchoolName-' . $rec->id . '" type="text" class="form-control" id="editSchoolName" value="' . $rec->school_name . '">
        </div>
        <div class="form-group float-label col-12 col-lg-9">
            <label for="editText" class="header-label">توضیحات</label>
            <input name="editText-' . $rec->id . '" type="text" class="form-control" id="editText" value="' . $rec->text . '">
        </div>

        ';
        }
        $data = csrf_field() . '
<input name="editId" type="hidden" id="editId" value="' . $resume->id . '">

   <div class="row"> ' . $records . '</div>


</div>';
        return $data;
    }

    public function editResumeLanguage($id)
    {
        $resume = Resume::where("id", $id)->with("languages")->first();
        $records = "";
        $educationRecords = $resume->languages;
        foreach ($educationRecords as $rec) {
            $records .= '<div class="form-group float-label col-12 col-lg-3">
            <label for="editTitle" class="header-label">نام زبان</label>
            <input name="editTitle-' . $rec->id . '" type="text" class="form-control" id="editTitle" value="' . $rec->title . '">
        </div>

        <div class="form-group float-label col-12 col-lg-3">
            <label for="editFluency" class="header-label">میزان تسلط</label>
            <input name="editFluency-' . $rec->id . '" type="text" class="form-control" id="editFluency" value="' . $rec->fluency_level . '">
        </div>
        <div class="form-group float-label col-12 col-lg-3">
            <label for="editDegree" class="header-label">مدرک</label>
            <input name="editDegree-' . $rec->id . '" type="text" class="form-control" id="editDegree" value="' . $rec->degree . '">
        </div>
        <div class="form-group float-label col-12 col-lg-3">
            <label for="editScore" class="header-label">نمره</label>
            <input name="editScore-' . $rec->id . '" type="text" class="form-control" id="editScore" value="' . $rec->score . '">
        </div>

        ';
        }
        $data = csrf_field() . '
<input name="editId" type="hidden" id="editId" value="' . $resume->id . '">
<input name="step" type="hidden" id="step" value="1">

   <div class="row"> ' . $records . '</div>


</div>';
        return $data;
    }

    public function editResumeWork($id)
    {
        $resume = Resume::where("id", $id)->with("works")->first();
        $records = "";
        $works = $resume->works;
        foreach ($works as $rec) {
            $records .= '<div class="form-group float-label col-12 col-lg-3">
            <label for="editCompanyName" class="header-label">نام شرکت</label>
            <input name="editCompanyName-' . $rec->id . '" type="text" class="form-control" id="editCompanyName" value="' . $rec->company_name . '">
        </div>

        <div class="form-group float-label col-12 col-lg-3">
            <label for="editPosition" class="header-label">سمت</label>
            <input name="editPosition-' . $rec->id . '" type="text" class="form-control" id="editPosition" value="' . $rec->position . '">
        </div>
        <div class="form-group float-label col-12 col-lg-2">
            <label for="editCity" class="header-label">شهر</label>
            <input name="editCity-' . $rec->id . '" type="text" class="form-control" id="editCity" value="' . $rec->city . '">
        </div>
        <div class="form-group float-label col-12 col-lg-2">
            <label for="editFromDateYear" class="header-label">از تاریخ سال</label>
            <input name="editFromDateYear-' . $rec->id . '" type="text" class="form-control" id="editFromDateYear" value="' . $rec->from_date_year . '">
        </div>
        <div class="form-group float-label col-12 col-lg-2">
            <label for="editFromDateMonth" class="header-label">از تاریخ ماه</label>
            <input name="editFromDateMonth-' . $rec->id . '" type="text" class="form-control" id="editFromDateMonth" value="' . $rec->from_date_month . '">
        </div>
        <div class="form-group float-label col-12 col-lg-2">
            <label for="editToDateYear" class="header-label">تا تاریخ سال</label>
            <input name="editToDateYear-' . $rec->id . '" type="text" class="form-control" id="editToDateYear" value="' . $rec->to_date_year . '">
        </div>
         <div class="form-group float-label col-12 col-lg-2">
            <label for="editToDateMonth" class="header-label">تا تاریخ ماه</label>
            <input name="editToDateMonth-' . $rec->id . '" type="text" class="form-control" id="editToDateMonth" value="' . $rec->to_date_month . '">
        </div>
        <div class="form-group float-label col-12">
            <label for="editText" class="header-label">توضیحات</label>
            <input name="editText-' . $rec->id . '" type="text" class="form-control" id="editText" value="' . $rec->text . '">
        </div>

        ';
        }
        $data = csrf_field() . '
<input name="editId" type="hidden" id="editId" value="' . $resume->id . '">
<input name="step" type="hidden" id="step" value="2">

   <div class="row"> ' . $records . '</div>


</div>';
        return $data;
    }

    public function editResumeSoftwareKnowledge($id)
    {
        $resume = Resume::where("id", $id)->with("softwareKnowledges")->first();
        $records = "";
        $softwareKnowledges = $resume->softwareKnowledges;
        foreach ($softwareKnowledges as $rec) {
            $records .= '<div class="form-group float-label col-12 col-lg-6">
            <label for="editTitle" class="header-label">نام زبان</label>
            <input name="editTitle-' . $rec->id . '" type="text" class="form-control" id="editTitle" value="' . $rec->title . '">
        </div>

        <div class="form-group float-label col-12 col-lg-6">
            <label for="editFluency" class="header-label">میزان تسلط</label>
            <input name="editFluency-' . $rec->id . '" type="text" class="form-control" id="editFluency" value="' . $rec->fluency_level . '">
        </div>


        ';
        }
        $data = csrf_field() . '
<input name="editId" type="hidden" id="editId" value="' . $resume->id . '">
<input name="step" type="hidden" id="step" value="3">

   <div class="row"> ' . $records . '</div>


</div>';
        return $data;
    }

    public function editResumeCourses($id)
    {
        $resume = Resume::where("id", $id)->with("courses")->first();
        $records = "";
        $courses = $resume->courses;
        foreach ($courses as $rec) {
            $records .= '<div class="form-group float-label col-12 col-lg-4">
            <label for="editTitle" class="header-label">عنوان</label>
            <input name="editTitle-' . $rec->id . '" type="text" class="form-control" id="editTitle" value="' . $rec->title . '">
        </div>

        <div class="form-group float-label col-12 col-lg-4">
            <label for="editOrganizer" class="header-label">برگزار کننده</label>
            <input name="editOrganizer-' . $rec->id . '" type="text" class="form-control" id="editOrganizer" value="' . $rec->organizer . '">
        </div>
         <div class="form-group float-label col-12 col-lg-4">
            <label for="editYear" class="header-label">تاریخ (سال)</label>
            <input name="editYear-' . $rec->id . '" type="text" class="form-control" id="editYear" value="' . $rec->year . '">
        </div>


        ';
        }
        $data = csrf_field() . '
<input name="editId" type="hidden" id="editId" value="' . $resume->id . '">
<input name="step" type="hidden" id="step" value="4">

   <div class="row"> ' . $records . '</div>


</div>';
        return $data;
    }

    public function editResumeResearch($id)
    {
        $resume = Resume::where("id", $id)->with("researchs")->first();
        $records = "";
        $researchs = $resume->researchs;
        foreach ($researchs as $rec) {
            $records .= '<div class="form-group float-label col-12 col-lg-3">
            <label for="editTitle" class="header-label">عنوان</label>
            <input name="editTitle-' . $rec->id . '" type="text" class="form-control" id="editTitle" value="' . $rec->title . '">
        </div>

        <div class="form-group float-label col-12 col-lg-3">
            <label for="editType" class="header-label">نوع</label>
            <input name="editType-' . $rec->id . '" type="text" class="form-control" id="editType" value="' . $rec->type . '">
        </div>
         <div class="form-group float-label col-12 col-lg-3">
            <label for="editYear" class="header-label">تاریخ (سال)</label>
            <input name="editYear-' . $rec->id . '" type="text" class="form-control" id="editYear" value="' . $rec->year . '">
        </div>
        <div class="form-group float-label col-12 col-lg-3">
            <label for="editText" class="header-label">توضیحات</label>
            <input name="editText-' . $rec->id . '" type="text" class="form-control" id="editText" value="' . $rec->text . '">
        </div>

        ';
        }
        $data = csrf_field() . '
<input name="editId" type="hidden" id="editId" value="' . $resume->id . '">
<input name="step" type="hidden" id="step" value="5">

   <div class="row"> ' . $records . '</div>


</div>';
        return $data;
    }

    public function editResumeHobby($id)
    {
        $resume = Resume::where("id", $id)->with("hobbies")->first();
        $records = "";
        $hobbies = $resume->hobbies;
        foreach ($hobbies as $rec) {
            $records .= '<div class="form-group float-label col-12 col-lg-6">
            <label for="editTitle" class="header-label">سرگرمی</label>
            <input name="editTitle-' . $rec->id . '" type="text" class="form-control" id="editTitle" value="' . $rec->title . '">
        </div>

        ';
        }
        $data = csrf_field() . '
<input name="editId" type="hidden" id="editId" value="' . $resume->id . '">
<input name="step" type="hidden" id="step" value="6">

   <div class="row"> ' . $records . '</div>


</div>';
        return $data;
    }

    public function editResumeDetails($id)
    {
        $resume = Resume::where("id", $id)->first();
        $data = csrf_field() . '<div  class="row">
<input name="editId" type="hidden" id="editId" value="' . $resume->id . '">
<input name="step" type="hidden" id="step" value="7">
<div class="form-group float-label col-12 col-lg-3">
            <label for="editName" class="header-label">نام </label>
            <input name="editName" type="text" class="form-control" id="editName" value="' . $resume->name . '">
        </div>
        <div class="form-group float-label col-12 col-lg-3">
            <label for="editFamily" class="header-label">نام خانوادگی</label>
            <input name="editFamily" type="text" class="form-control" id="editFamily" value="' . $resume->family . '">
        </div>

       <div class="form-group float-label col-12 col-lg-3">
            <label for="editLang" class="header-label">زبان</label>
            <input name="editLang" type="text" class="form-control" id="editLang" value="' . $resume->language . '">
        </div>
         <div class="form-group float-label col-12 col-lg-3">
            <label for="editBirthDate" class="header-label">تاریخ تولد</label>
            <input name="editBirthDate" type="text" class="form-control" id="editBirthDate" value="' . $resume->birth_date . '">
        </div>
        <div class="form-group float-label col-12 col-lg-3">
            <label for="editBirthPlace" class="header-label">محل تولد</label>
            <input name="editBirthPlace" type="text" class="form-control" id="editBirthPlace" value="' . $resume->birth_place . '">
        </div>
         <div class="form-group float-label col-12 col-lg-3">
            <label for="editPhone" class="header-label">موبایل</label>
            <input name="editPhone" type="text" class="form-control" id="editPhone" value="' . $resume->phone . '">
        </div>
        <div class="form-group float-label col-12 col-lg-3">
            <label for="editEmail" class="header-label">ایمیل</label>
            <input name="editEmail" type="text" class="form-control" id="editEmail" value="' . $resume->email . '">
        </div>
        <div class="form-group float-label col-12 col-lg-3">
            <label for="editAddress" class="header-label">آدرس</label>
            <input name="editAddress" type="text" class="form-control" id="editAddress" value="' . $resume->address . '">
        </div>
        <div class="form-group float-label col-12 col-lg-3">
            <label for="editSocialMedia" class="header-label">شبکه های اجتماعی</label>
            <input name="editSocialMedia" type="text" class="form-control" id="editSocialMedia" value="' . $resume->socialmedia_links . '">
        </div>
        </div>


</div>';
        return $data;
    }

    public function editRequest($id)
    {
        $resume = Resume::where("id", $id)->first();
        $data = csrf_field() . '
<input name="editId" type="hidden" id="editId" value="' . $resume->id . '">
<input name="step" type="hidden" id="step" value="9">
<div class="form-group float-label col-12 col-lg-12">
   <textarea class="editor" name="text"> ' . $resume->admin_comment . '</textarea>
</div>  <div class="col-12 col-lg-3">
<div class="form-group float-label">
    <label for="file" class="header-label">فایل پیوست</label>
    <input type="file" name="file" class="form-control form-control-sm" id="file">
</div>
</div>
<div class="col-12 col-lg-3">
<div class="form-group float-label">
    <label for="file2" class="header-label">فایل پیوست 2</label>
    <input type="file" name="file2" class="form-control form-control-sm" id="file2">
</div>
</div>
</div>

</div>';
        return $data;
    }

    public function updateResume(Request $request)
    {

        $step = $request->step;
        if ($step == 0) {
            $rules = $this->listRuleGenerator($request, 'editGrade', [
                'editGrade' => 'required|max:200|bad_chars',
                'editField' => 'required|max:200|bad_chars',
                'editFromDate' => 'date|bad_chars|date_format:Y-m-d',
                'editToDate' => 'date|bad_chars|date_format:Y-m-d',
                'editCity' => 'required|max:200|bad_chars',
                'editScore' => 'required|min:0|max:100|numeric',
                'editSchoolName' => 'required|max:200|bad_chars',
                'editText' => 'max:200|bad_chars',
            ]);
        } elseif ($step == 1) {
            $rules = $this->listRuleGenerator($request, 'editTitle', [
                'editTitle' => 'required|max:200|bad_chars',
                'editFluency' => 'required|max:200|bad_chars',
                'editDegree' => 'required|max:200|bad_chars',
                'editScore' => 'required|min:0|max:100|numeric',
            ]);
        } elseif ($step == 2) {
            $rules = $this->listRuleGenerator($request, 'editCompanyName', [
                'editCompanyName' => 'required|max:200|bad_chars',
                'editPosition' => 'required|max:200|bad_chars',
                'editCity' => 'required|max:200|bad_chars',
                'editText' => 'max:200|bad_chars',
                'editFromDate' => 'date|bad_chars|date_format:Y-m-d',
                'editToDate' => 'date|bad_chars|date_format:Y-m-d',
            ]);
        } elseif ($step == 3) {
            $rules = $this->listRuleGenerator($request, 'editTitle', [
                'editTitle' => 'required|max:200|bad_chars',
                'editFluency' => 'required|max:100|numeric',
            ]);
        } elseif ($step == 4) {
            $rules = $this->listRuleGenerator($request, 'editTitle', [
                'editTitle' => 'required|max:200|bad_chars',
                'editOrganizer' => 'required|max:200|bad_chars',
                'editYear' => 'required|max:9999|numeric',
            ]);
        } elseif ($step == 5) {
            $rules = $this->listRuleGenerator($request, 'editTitle', [
                'editTitle' => 'required|max:200|bad_chars',
                'editType' => 'required|max:200|bad_chars',
                'editText' => 'max:200|bad_chars',
                'editYear' => 'required|max:9999|numeric',
            ]);
        } elseif ($step == 6) {
            $rules = $this->listRuleGenerator($request, 'editHobby', [
                'editHobby' => 'required|max:200|bad_chars',
            ]);
        } elseif ($step == 0) {
            $rules = [
                'editName' => 'required|max:200|bad_chars',
                'editFamily' => 'required|max:200|bad_chars',
                'editLang' => 'required|max:200|bad_chars',
                'editBirthDate' => 'date|bad_chars|date_format:Y-m-d',
                'editBirthPlace' => 'required|max:200|bad_chars',
                'editEmail' => 'nullable|email|max:200',
                'editPhone' => 'required|min:10|max:11',
                'editAddress' => 'max:200|bad_chars',
                'editSocialMedia' => 'max:200|bad_chars',
            ];
        } elseif ($step == 9) {
            $rules = [
                'text' => 'required',
                'file' => 'mimes:pdf|max:100000',
            ];
        }
        $validator = validator()->make($request->all(), $rules);
        $first_error = "";
        $errors = $validator->errors();
        if ($validator->fails()) {
            foreach ($errors->toArray() as $key => $error) {
                $first_error = $error[0];
                return response()->json(['errors' => $errors, "msg" => $first_error, "type" => "warning"]);
            }
        }
        if ($step == 0) {
            foreach ($request->all() as $key => $field) {
                if (str_contains($key, "editGrade-")) {
                    $m = ResumeEducationRecord::find(intval(str_replace("editGrade-", "", $key)));
                    $m->grade = $field;
                    $m->save();
                }
                if (str_contains($key, "editField-")) {
                    $m = ResumeEducationRecord::find(intval(str_replace("editField-", "", $key)));
                    $m->field = $field;
                    $m->save();
                }
                if (str_contains($key, "editFromDateYear-")) {
                    $m = ResumeEducationRecord::find(intval(str_replace("editFromDateYear-", "", $key)));
                    $m->from_date_year = $field;
                    $m->save();
                }
                if (str_contains($key, "editFromDateMonth-")) {
                    $m = ResumeEducationRecord::find(intval(str_replace("editFromDateMonth-", "", $key)));
                    $m->from_date_month = $field;
                    $m->save();
                }
                if (str_contains($key, "editToDateYear-")) {
                    $m = ResumeEducationRecord::find(intval(str_replace("editToDateYear-", "", $key)));
                    $m->to_date_year = $field;
                    $m->save();
                }
                if (str_contains($key, "editToDateMonth-")) {
                    $m = ResumeEducationRecord::find(intval(str_replace("editToDateMonth-", "", $key)));
                    $m->to_date_month = $field;
                    $m->save();
                }
                if (str_contains($key, "editCity-")) {
                    $m = ResumeEducationRecord::find(intval(str_replace("editCity-", "", $key)));
                    $m->city = $field;
                    $m->save();
                }
                if (str_contains($key, "editSchoolName-")) {
                    $m = ResumeEducationRecord::find(intval(str_replace("editSchoolName-", "", $key)));
                    $m->school_name = $field;
                    $m->save();
                }
                if (str_contains($key, "editScore-")) {
                    $m = ResumeEducationRecord::find(intval(str_replace("editScore-", "", $key)));
                    $m->grade_score = $field;
                    $m->save();
                }
                if (str_contains($key, "editText-")) {
                    $m = ResumeEducationRecord::find(intval(str_replace("editText-", "", $key)));
                    $m->text = $field;
                    $m->save();
                }
            }
        } elseif ($step == 1) {
            foreach ($request->all() as $key => $field) {
                if (str_contains($key, "editTitle-")) {
                    $m = ResumeLanguage::find(intval(str_replace("editTitle-", "", $key)));
                    $m->title = $field;
                    $m->save();
                }
                if (str_contains($key, "editFluency-")) {
                    $m = ResumeLanguage::find(intval(str_replace("editFluency-", "", $key)));
                    $m->fluency_level = $field;
                    $m->save();
                }
                if (str_contains($key, "editDegree-")) {
                    $m = ResumeLanguage::find(intval(str_replace("editDegree-", "", $key)));
                    $m->degree = $field;
                    $m->save();
                }
                if (str_contains($key, "editScore-")) {
                    $m = ResumeLanguage::find(intval(str_replace("editScore-", "", $key)));
                    $m->score = $field;
                    $m->save();
                }
            }
        } elseif ($step == 2) {
            foreach ($request->all() as $key => $field) {
                if (str_contains($key, "editCompanyName-")) {
                    $m = ResumeWork::find(intval(str_replace("editCompanyName-", "", $key)));
                    $m->company_name = $field;
                    $m->save();
                }
                if (str_contains($key, "editFromDateYear-")) {
                    $m = ResumeWork::find(intval(str_replace("editFromDateYear-", "", $key)));
                    $m->from_date_year = $field;
                    $m->save();
                }
                if (str_contains($key, "editFromDateMonth-")) {
                    $m = ResumeWork::find(intval(str_replace("editFromDateMonth-", "", $key)));
                    $m->from_date_month = $field;
                    $m->save();
                }
                if (str_contains($key, "editToDateYear-")) {
                    $m = ResumeWork::find(intval(str_replace("editToDateYear-", "", $key)));
                    $m->to_date_year = $field;
                    $m->save();
                }
                if (str_contains($key, "editToDateMonth-")) {
                    $m = ResumeWork::find(intval(str_replace("editToDateMonth-", "", $key)));
                    $m->to_date_month = $field;
                    $m->save();
                }
                if (str_contains($key, "editPosition-")) {
                    $m = ResumeWork::find(intval(str_replace("editPosition-", "", $key)));
                    $m->position = $field;
                    $m->save();
                }
                if (str_contains($key, "editCity-")) {
                    $m = ResumeWork::find(intval(str_replace("editCity-", "", $key)));
                    $m->city = $field;
                    $m->save();
                }
                if (str_contains($key, "editText-")) {
                    $m = ResumeWork::find(intval(str_replace("editText-", "", $key)));
                    $m->text = $field;
                    $m->save();
                }
            }
        } elseif ($step == 3) {
            foreach ($request->all() as $key => $field) {
                if (str_contains($key, "editTitle-")) {
                    $m = ResumeSoftwareKnowledge::find(intval(str_replace("editTitle-", "", $key)));
                    $m->title = $field;
                    $m->save();
                }
                if (str_contains($key, "editFluency-")) {
                    $m = ResumeSoftwareKnowledge::find(intval(str_replace("editFluency-", "", $key)));
                    $m->fluency_level = $field;
                    $m->save();
                }
            }
        } elseif ($step == 4) {
            foreach ($request->all() as $key => $field) {
                if (str_contains($key, "editTitle-")) {
                    $m = ResumeCourse::find(intval(str_replace("editTitle-", "", $key)));
                    $m->title = $field;
                    $m->save();
                }
                if (str_contains($key, "editOrganizer-")) {
                    $m = ResumeCourse::find(intval(str_replace("editOrganizer-", "", $key)));
                    $m->organizer = $field;
                    $m->save();
                }
                if (str_contains($key, "editYear-")) {
                    $m = ResumeCourse::find(intval(str_replace("editYear-", "", $key)));
                    $m->year = $field;
                    $m->save();
                }
            }
        } elseif ($step == 5) {
            foreach ($request->all() as $key => $field) {
                if (str_contains($key, "editType-")) {
                    $m = ResumeResearch::find(intval(str_replace("editType-", "", $key)));
                    $m->type = $field;
                    $m->save();
                }
                if (str_contains($key, "editTitle-")) {
                    $m = ResumeResearch::find(intval(str_replace("editTitle-", "", $key)));
                    $m->title = $field;
                    $m->save();
                }
                if (str_contains($key, "editYear-")) {
                    $m = ResumeResearch::find(intval(str_replace("editYear-", "", $key)));
                    $m->year = $field;
                    $m->save();
                }
                if (str_contains($key, "editText-")) {
                    $m = ResumeResearch::find(intval(str_replace("editText-", "", $key)));
                    $m->text = $field;
                    $m->save();
                }
            }
        } elseif ($step == 6) {
            foreach ($request->all() as $key => $field) {
                if (str_contains($key, "editHobby-")) {
                    $m = ResumeHobby::find(intval(str_replace("editHobby-", "", $key)));
                    $m->hobby = $field;
                    $m->save();
                }
            }
        } elseif ($step == 0) {
            $resume = Resume::find($request->editId);
            $resume->name = $request->editName;
            $resume->family = $request->editFamily;
            $resume->birth_date = $request->editBirthDate;
            $resume->birth_place = $request->editBirthPlace;
            $resume->socialmedia_links = $request->editSocialMedia;
            $resume->phone = $request->editPhone;
            $resume->email = $request->editEmail;
            $resume->address = $request->editAddress;
            $resume->language = $request->editLang;
            if (!$resume->save()) {
                return response()->json(['errors' => [], "msg" => "خطای دیتابیس", "type" => "warning"]);
            }
        } elseif ($step == 9) {
            $resume = Resume::find($request->editId);
            $resume->admin_comment = $request->text;
            $admin_attachment = "";
            if ($request->file('file')) {
                $folder = '/uploads/resumeAdminAttachment/';
                $file = $request->file('file');
                $inserted_acceptance[] = 6;
                $file->move(public_path() . $folder, $resume->id . '_1.pdf');
                $admin_attachment .= route('resumeAdminAttachment', ["id" => $resume->id, "pos" => 1]);
            }
            if ($request->file('file2')) {
                $folder = '/uploads/resumeAdminAttachment/';
                $file = $request->file('file2');
                $inserted_acceptance[] = 6;
                $file->move(public_path() . $folder, $resume->id . '_2.pdf');
                $admin_attachment .= "," . route('resumeAdminAttachment', ["id" => $resume->id, "pos" => 2]);
            }
            if ($request->file('file3')) {
                $folder = '/uploads/resumeAdminAttachment/';
                $file = $request->file('file');
                $inserted_acceptance[] = 6;
                $file->move(public_path() . $folder, $resume->id . '_3.pdf');
                $admin_attachment .= "," . route('resumeAdminAttachment', ["id" => $resume->id, "pos" => 3]);
            }
            if ($request->file('file4')) {
                $folder = '/uploads/resumeAdminAttachment/';
                $file = $request->file('file');
                $inserted_acceptance[] = 6;
                $file->move(public_path() . $folder, $resume->id . '_4.pdf');
                $admin_attachment .= "," . route('resumeAdminAttachment', ["id" => $resume->id, "pos" => 4]);
            }
            $resume->admin_attachment = $admin_attachment;
            if (!$resume->save()) {
                return response()->json(['errors' => [], "msg" => "خطای دیتابیس", "type" => "warning"]);
            }
            $name = $resume->user->firstname;
            $order = $resume->id;
            $send = (new SMS())->sendVerification($resume->user->mobile, "resume_edit_needed", "name=={$name}&order=={$order}");
            User::sendMail(new MailVerificationCode("resume_edit_needed", [
                $name,
                $order,
            ], "resume_edit_needed"), $resume->user->email);
            $notif = (new Notification("resume_edit_needed", [$name, $order]))->send($resume->user->id);
        }
        $resume = Resume::find($request->editId);
        $resume->status = 3;
        $resume->save();
        return response()->json(['errors' => [], "msg" => "انجام شد", "type" => "success"]);
    }

    public function listRuleGenerator(Request $request, $index, $array)
    {
        $count = 0;
        foreach ($request->all() as $key => $field) {
            if (str_contains($key, "{$index}-")) {
                $count++;
            }
        }
        $newRules = [];
        foreach ($array as $key => $value) {
            for ($i = 1; $i <= $count; $i++) {
                $newRules[$key . "-" . $i] = $value;
            }
        }
        return $newRules;
    }

    public function motivations()
    {
        $motivations = Motivation::orderBy('updated_at', 'DESC')->where("status", ">", 0)->with('user')->paginate(10);
        $users = User::select('id', 'firstname', 'lastname', 'mobile', 'level')->get();
        $categories = Category::all();

        return view('admin.motivations.motivations', compact('motivations', 'users', 'categories'));
    }

    public function editMotivation(Request $req, $id)
    {
        if ($req->step) {
            $motivation = Motivation::where("id", $id)->where("status", ">", 0)->first();
            $data = csrf_field() . '
    <input name="editId" type="hidden" id="editId" value="' . $motivation->id . '">
    <input name="step" type="hidden" id="step" value="1">

<div class="form-group float-label col-12 col-lg-12">
   <textarea class="editor" name="text"> ' . $motivation->admin_comment . '</textarea>
</div>
    <div class="col-12 col-lg-3">
        <div class="form-group float-label">
            <label for="file" class="header-label">فایل پیوست</label>
            <input type="file" name="file" class="form-control form-control-sm" id="file">
        </div>
    </div>
    <div class="col-12 col-lg-3">
        <div class="form-group float-label">
            <label for="file2" class="header-label">فایل پیوست 2</label>
            <input type="file" name="file2" class="form-control form-control-sm" id="file2">
        </div>
    </div>
    <div class="col-12 col-lg-3">
        <div class="form-group float-label">
            <label for="file3" class="header-label">فایل پیوست 3</label>
            <input type="file" name="file3" class="form-control form-control-sm" id="file3">
        </div>
    </div>
    <div class="col-12 col-lg-3">
        <div class="form-group float-label">
            <label for="file4" class="header-label">فایل پیوست 4</label>
            <input type="file" name="file4" class="form-control form-control-sm" id="file4">
        </div>
    </div>

</div>';
            return $data;
        }
        $motivation = Motivation::where("id", $id)->where("status", ">", 0)->with("user")->with('universities')->first();
        $universities = $motivation->universities;
        $university_section = "";
        $form_id = 0;
        foreach ($universities as $university) {
            $form_id++;
            $university_section .= '<div class="form-group float-label col-12 col-lg-3">
            <label for="editUniversity" class="header-label">نام دانشگاه</label>
            <input name="editUniversity-' . $university->id . '" type="text" class="form-control" id="editUniversity" value="' . $university->name . '">
        </div>

        <div class="form-group float-label col-12 col-lg-3">
            <label for="editField" class="header-label">رشته</label>
            <input name="editField-' . $university->id . '" type="text" class="form-control" id="editField" value="' . $university->field . '">
        </div>
        <div class="form-group float-label col-12 col-lg-3">
            <label for="editStage" class="header-label">مقطع تحصیلی</label>
            <input name="editStage-' . $university->id . '" type="text" class="form-control" id="editStage" value="' . $university->grade . '">
        </div>
        <div class="form-group float-label col-12 col-lg-3">
            <label for="editLang" class="header-label">زبان تحصیل </label>
            <input name="editLang-' . $university->id . '" type="text" class="form-control" id="editLang" value="' . $university->language . '">
        </div>';
        }
        $data = csrf_field() . '
<input name="editId" type="hidden" id="editId" value="' . $motivation->id . '">
<div class="row">
     <div class="form-group float-label col-12 col-lg-4">
        <label for="editFirstname" class="header-label">نام</label>
        <input name="editFirstname" type="text" class="form-control" id="editFirstname" value="' . $motivation->name . '">
    </div>
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editLastname" class="header-label">نام خانوادگی</label>
        <input name="editLastname" type="text" class="form-control" id="editLastname" value="' . $motivation->family . '">
    </div>

    <div class="form-group float-label col-12 col-lg-4">
        <label for="editEmail" class="header-label">ایمیل</label>
        <input name="editEmail" type="text" class="form-control" id="editEmail" value="' . $motivation->email . '">
    </div>
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editMobile" class="header-label">موبایل</label>
        <input name="editMobile" type="text" class="form-control" id="editMobile" value="' . $motivation->phone . '">
    </div>

    <div class="form-group float-label col-12 col-lg-4">
        <label for="editCity" class="header-label">محل تولد</label>
        <input name="editCity" type="text" class="form-control" id="editCity" value="' . $motivation->birth_place . '">
    </div>
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editAddress" class="header-label">ادرس</label>
        <input name="editAddress" type="text" class="form-control" id="editAddress" value="' . $motivation->address . '">
    </div>
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editBirthDate" class="header-label">تاریخ تولد</label>
        <input name="editBirthDate" type="text" class="form-control" id="editBirthDate" value="' . $motivation->birth_date . '">
    </div>

  <div class="form-group float-label col-12 col-lg-4">
        <label for="editTo" class="header-label">ارائه به ؟</label>
        <select name="editTo" type="text" class="form-control" id="editTo">
            <option value="2" ' . ($motivation->to == 2 ? 'selected' : '') . '>دانشگاه</option>
            <option value="1" ' . ($motivation->to == 1 ? 'selected' : '') . '>سفارت</option>
        </select>
    </div>
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editToExtra" class="header-label">انتخاب سفارت</label>
        <select name="editToExtra" type="text" class="form-control"  id="editToExtra">
            <option value="1" ' . ($motivation->country == 1 ? 'selected' : '') . '>ایران</option>
            <option value="2" ' . ($motivation->country == 2 ? 'selected' : '') . '>سایرکشورها</option>
        </select>
    </div>

    ' . $university_section . '
    <div class="my-3"><hr></div>
     <div class="form-group float-label col-12 col-lg-6">
        <label for="editAbout" class="header-label">درباره</label>
        <textarea name="editAbout" type="text" class="form-control" id="editAbout">' . $motivation->about . '</textarea>
    </div>
    <div class="form-group float-label col-12 col-lg-6">
        <label for="editResume" class="header-label">سوابق کاری</label>
        <textarea name="editResume" type="text" class="form-control" id="editResume">' . $motivation->resume . '</textarea>
    </div>

     <div class="form-group float-label col-12 col-lg-6">
        <label for="editWhyGermany" class="header-label">چرا آلمان</label>
        <textarea name="editWhyGermany" type="text" class="form-control" id="editWhyGermany">' . $motivation->why_germany . '</textarea>
    </div>
     <div class="form-group float-label col-12 col-lg-6">
        <label for="editAfter" class="header-label">بعد از فارغ التحصیلی</label>
        <textarea name="editAfter" type="text" class="form-control" id="editAfter">' . $motivation->after_graduation . '</textarea>
    </div>
      <div class="form-group float-label col-12">
        <label for="editExtraText" class="header-label">توضیح اضافی</label>
        <textarea name="editExtraText" type="text" class="form-control" id="editExtraText">' . $motivation->extra_text . '</textarea>
    </div>




</div>';
        return $data;
    }

    public function getMotivations(Request $request)
    {
        $motivation = Motivation::Query()->where("status", ">", 0);
        if ($request->searchId) {
            $motivation->where('id', $request->searchId);
        }
        if ($request->searchUser) {
            $motivation->where('user_id', $request->searchUser);
        }
        if ($request->searchTerm) {
            $users = User::where('category_id', $request->searchTerm)->pluck('id');
            $motivation->whereIn('user_id', $users);
        }
        if ($request->searchWriter) {
            $motivation->where('writer_id', $request->searchWriter);
        }
        if ($request->searchTitle) {
            $motivation->where('title', 'LIKE', '%' . $request->searchTitle . '%');
        }
        if ($request->searchStartDate) {
            $date = MyHelpers::numberToEnglish(explode('/', $request->searchStartDate));
            $year = $date[0];
            $month = $date[1];
            $day = $date[2];
            $date = JDF::jalali_to_gregorian($year, $month, $day, '-');

            $motivation->where('created_at', '>=', $date);
        }
        if ($request->searchEndDate) {
            $date = MyHelpers::numberToEnglish(explode('/', $request->searchEndDate));
            $year = $date[0];
            $month = $date[1];
            $day = $date[2];
            $date = JDF::jalali_to_gregorian($year, $month, $day, '-');

            $motivation->where('created_at', '<=', $date);
        }
        $motivations = $motivation->orderBy('updated_at', 'DESC')->paginate(10);
        $categories = Category::all();

        return view('admin.motivations.list', compact('motivations', 'categories'))->render();
    }

    public function updateMotivation(Request $request)
    {
        if ($request->step) {
            $rules = [
                'text' => 'required',
            ];
        } else {
            $rules = [
                'editEmail' => 'nullable|email|max:200',
                'editMobile' => 'required|min:10|max:11',
                'editFirstname' => 'required|max:200|bad_chars',
                'editLastname' => 'required|max:200|bad_chars',
                'editCity' => 'required|max:200|bad_chars',
                'editBirthDate' => 'required|max:200|bad_chars',
                'editAddress' => 'required|max:200|bad_chars',
            ];
        }
        $customMessages = [
            'editEmail.email' => 'ایمیل معتبر نیست',
            'editEmail.max' => 'ایمیل حداکثر 200 کاراکتر باید باشد',
            'editMobile.required' => 'موبایل را وارد کنید',
            'editMobile.max' => 'موبایل حداکثر 11 رقم است',
            'editMobile.min' => 'موبایل حداقل 10 رقم است',
            'editFirstname.required' => 'ورود نام الزامی است',
            'editFirstname.max' => 'نام حداکثر باید 200 کاراکتر باشد',
            'editFirstname.bad_chars' => 'نام حاوی کاراکتر های غیر مجاز است',
            'editCity.required' => 'ورود محل تولد الزامی است',
            'editCity.max' => 'محل تولد حداکثر باید 200 کاراکتر باشد',
            'editCity.bad_chars' => 'محل تولد حاوی کاراکتر های غیر مجاز است',
            'editBirthDate.required' => 'ورود تاریخ تولد الزامی است',
            'editBirthDate.max' => 'تاریخ تولد حداکثر باید 200 کاراکتر باشد',
            'editBirthDate.bad_chars' => 'تاریخ تولد حاوی کاراکتر های غیر مجاز است',
            'editAddress.required' => 'ورود آدرس الزامی است',
            'editAddress.max' => 'آدرس حداکثر باید 200 کاراکتر باشد',
            'editAddress.bad_chars' => 'آدرس حاوی کاراکتر های غیر مجاز است',
            'editLastname.required' => 'ورود نام خانوادگی الزامی است',
            'editLastname.max' => 'نام خانوادگی حداکثر باید 200 کاراکتر باشد',
            'editLastname.bad_chars' => 'نام خانوادگی حاوی کاراکتر های غیر مجاز است',
            'editPassword.confirmed' => 'گذرواژه با تایپ مجدد آن خوانایی ندارد',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        $first_error = "";
        $errors = $validator->errors();
        if ($validator->fails()) {
            foreach ($errors->toArray() as $key => $error) {
                $first_error = $error[0];
                break;
            }
            return response()->json(['errors' => $errors, "msg" => $first_error, "type" => "warning"]);
        }
        $motivation = Motivation::find($request->editId);
        if ($request->step) {
            $motivation->admin_comment = $request->text;
            $admin_attachment = [];
            if ($request->file('file')) {
                $folder = '/uploads/motivationAdminAttachment/';
                $file = $request->file('file');
                $inserted_acceptance[] = 6;
                $file->move(public_path() . $folder, $motivation->id . '_1.pdf');
                $admin_attachment[] = route('motivationAdminAttachment', ["id" => $motivation->id, "pos" => 1]);
            }
            if ($request->file('file2')) {
                $folder = '/uploads/motivationAdminAttachment/';
                $file = $request->file('file2');
                $inserted_acceptance[] = 6;
                $file->move(public_path() . $folder, $motivation->id . '_2.pdf');
                $admin_attachment[] = route('motivationAdminAttachment', ["id" => $motivation->id, "pos" => 2]);
            }
            if ($request->file('file3')) {
                $folder = '/uploads/motivationAdminAttachment/';
                $file = $request->file('file');
                $inserted_acceptance[] = 6;
                $file->move(public_path() . $folder, $motivation->id . '_3.pdf');
                $admin_attachment[] = route('motivationAdminAttachment', ["id" => $motivation->id, "pos" => 3]);
            }
            if ($request->file('file4')) {
                $folder = '/uploads/motivationAdminAttachment/';
                $file = $request->file('file');
                $inserted_acceptance[] = 6;
                $file->move(public_path() . $folder, $motivation->id . '_4.pdf');
                $admin_attachment[] = route('motivationAdminAttachment', ["id" => $motivation->id, "pos" => 4]);
            }
            $motivation->admin_attachment = json_encode($admin_attachment);
            $name = $motivation->user->firstname;
            $order = $motivation->id;
            $send = (new SMS())->sendVerification($motivation->user->mobile, "motivation_edit_needed", "name=={$name}&order=={$order}");

            User::sendMail(new MailVerificationCode("motivation_edit_needed", [
                $name,
                $order,
            ], "motivation_edit_needed"), $motivation->user->email);
            $notif = (new Notification("motivation_edit_needed", [$name, $order]))->send($motivation->user->id);
        } else {
            $motivation->name = $request->editFirstname;
            $motivation->family = $request->editLastname;
            $motivation->phone = $request->editMobile;
            $motivation->email = $request->editEmail;
            $motivation->birth_date = $request->editBirthDate;
            $motivation->birth_place = $request->editCity;
            $motivation->address = $request->editAddress;
            $motivation->to = $request->editTo;
            $motivation->country = $request->editToExtra ?? "";
            $motivation->about = $request->editAbout;
            $motivation->why_germany = $request->editWhyGermany;
            $motivation->after_graduation = $request->editAfter;
            $motivation->resume = $request->editResume;
            $motivation->extra_text = $request->editExtraText;
            foreach ($request->all() as $key => $field) {
                if (str_contains($key, "editUniversity-")) {
                    $m = MotivationUniversity::find(intval(str_replace("editUniversity-", "", $key)));
                    $m->name = $field;
                    $m->save();
                }
                if (str_contains($key, "editField-")) {
                    $m = MotivationUniversity::find(intval(str_replace("editField-", "", $key)));
                    $m->field = $field;
                    $m->save();
                }
                if (str_contains($key, "editStage-")) {
                    $m = MotivationUniversity::find(intval(str_replace("editStage-", "", $key)));
                    $m->grade = $field;
                    $m->save();
                }
                if (str_contains($key, "editLang-")) {
                    $m = MotivationUniversity::find(intval(str_replace("editLang-", "", $key)));
                    $m->language = $field;
                    $m->save();
                }
            }
        }
        $motivation->status = 3;
        if ($motivation->save()) {
            return response()->json(['errors' => [], "msg" => "انجام شد", "type" => "success"]);
        }
        return response()->json(['errors' => [], "msg" => "خطای دیتابیس", "type" => "warning"]);
    }

    public function deleteMotivation($id)
    {
        $motivation = Motivation::find($id);
        ResumeMotivationIds::where('model_id', $id)->delete();
        if ($motivation->delete()) {
            session()->flash('success', 'انگیزه نامه حذف شد');
        } else {
            session()->flash('error', 'خطا در حذف انگیزه نامه');
        }
        return redirect()->back();
    }

    public function showMotivation($id)
    {
        $motivation = Motivation::find($id);
        $motivation->url_uploaded_from_user = json_decode($motivation->url_uploaded_from_user, true);
        $motivation->url_uploaded_from_admin = json_decode($motivation->url_uploaded_from_admin, true);
        $motivation->url_uploaded_from_writer = json_decode($motivation->url_uploaded_from_writer, true);
        $motivation->admin_attachment = json_decode($motivation->admin_attachment, true);
        $writers = User::where('level', 6)->get();
        if (!is_array($motivation->admin_attachment)) {
            $motivation->admin_attachment = [];
        }
        if (!is_array($motivation->url_uploaded_from_admin)) {
            $motivation->url_uploaded_from_admin = [];
        }
        if (!is_array($motivation->url_uploaded_from_user)) {
            $motivation->url_uploaded_from_user = [];
        }
        return view('admin.motivations.show', compact('motivation', 'writers'));
    }

    public function downloadResumeExcel($id)
    {
        return Excel::download(new ResumeExport($id), 'resume_' . time() . '.xlsx');
    }

    public function downloadMotivationExcel($id)
    {
        return Excel::download(new MotivationExport($id), 'motivation_' . time() . '.xlsx');
    }

    function showMotivationPreview($id)
    {
        $motivation = Motivation::find($id);
        return view('admin.motivations.preview.index', compact('motivation'));
    }

    function downloadMotivationPreview($id)
    {
        $motivation = Motivation::where('id', $id)->first();
        $pdf = PDF::loadView('admin.motivations.preview.pdf', [
            'motivation' => $motivation
        ], [], [
            'subject' => $motivation->id . 'انگیزه نامه ',
        ]);
        return $pdf->stream($motivation->id . ' ' . $motivation->created_at . '.pdf');
    }

    function previewResume($id)
    {
        $resume = Resume::find($id);
        return view('admin.resumes.preview.index', compact('resume'));
    }

    function downloadResumePreview($id)
    {
        $resume = Resume::find($id);
        $pdf = PDF::loadView('admin.resumes.preview.pdf', [
            'resume' => $resume
        ], [], [
            'subject' => $resume->id . 'رزومه ',
        ]);
        return $pdf->stream($resume->id . ' ' . $resume->created_at . '.pdf');
    }

    function addWriterToMotivation(Request $request, $id)
    {
        if ($request->writer) {
            $motivation = Motivation::find($id);
            $motivation->writer_id = $request->writer;

            if (UserSupervisor::where('user_id', $motivation->user_id)
                    ->where('supervisor_id', $request->writer)
                    ->count() == 0) {
                $sup = new UserSupervisor();
                $sup->user_id = $motivation->user_id;
                $sup->supervisor_id = $request->writer;
                $sup->save();
            }
            $writer=User::where('id',$request->writer)->select('id','email')->first();
            if ($motivation->save()) {
                //Send notification and email
                $mail = User::sendMail(new MailVerificationCode("add_writer", [
                    $motivation->user->firstname . " " . $motivation->user->lastname,
                    'انگیزه نامه'
                ], "add_writer"), $writer->email);
                $notif = (new Notification("add_writer", [
                    $motivation->user->firstname . " " . $motivation->user->lastname,
                    'انگیزه نامه'
                ]))->send($writer->id);

                session()->flash('success', 'نگارنده با موفقیت ثبت گردید');
                return redirect()->back();
            } else {
                session()->flash('error', 'ثبت با شکست مواجه گردید');
                return redirect()->back();
            }
        } else {
            session()->flash('error', 'نگارنده را انتخاب نمایید.');
            return redirect()->back();
        }
    }

    function acceptMotivationFile($id, $file)
    {
        $motivation = Motivation::find($id);
        $files = json_decode($motivation->url_uploaded_from_writer);

        foreach ($files as $key => $item) {
            if ($key === intval($file)) {
                $motivation->admin_accepted_filename = $item;
                $motivation->status = 2;
                break;
            }
        }
        $motivation->is_accepted=null;
        if ($motivation->save()) {

            $name = $motivation->user->firstname . ' ' . $motivation->user->lastname;
            $sups = UserSupervisor::where('user_id', $motivation->user->id)
                ->whereNotIn('supervisor_id', [26, $motivation->writer_id])->get();
            foreach ($sups as $sup) {
                if ($sup->supervisor->level === 5 || $sup->supervisor->level === 2) {
                    $send = User::sendMail(new MailVerificationCode("admin_file_upload_accept", [
                        'انگیزه نامه',
                        $name,
                    ], "admin_file_upload_accept"), $sup->supervisor->email);
                    $notif = (new Notification("admin_file_upload_accept", [
                        'انگیزه نامه',
                        $name,
                    ]))->send($sup->supervisor->id);
                }
            }

            $send = User::sendMail(new MailVerificationCode("admin_file_upload_accept", [
                'انگیزه نامه',
                $name,
            ], "admin_file_upload_accept"), $motivation->writer->email);

            session()->flash('success', 'تایید فایل با موفقیت ثبت گردید');
            return redirect()->back();
        } else {
            session()->flash('error', 'تایید فایل با شکست مواجه گردید');
            return redirect()->back();
        }
    }

    function acceptResumeFile($id, $file)
    {
        $resume = Resume::find($id);
        $files = json_decode($resume->url_uploaded_from_writer);

        foreach ($files as $key => $item) {
            if ($key === intval($file)) {
                $resume->admin_accepted_filename = $item;
                $resume->status = 5;
                break;
            }
        }
        $resume->is_accepted=null;
        if ($resume->save()) {
            $name = $resume->user->firstname . ' ' . $resume->user->lastname;
            $sups = UserSupervisor::where('user_id', $resume->user->id)
                ->whereNotIn('supervisor_id', [26, $resume->writer_id])->get();
            foreach ($sups as $sup) {
                if ($sup->supervisor->level === 5 || $sup->supervisor->level === 2) {
                    $send = User::sendMail(new MailVerificationCode("admin_file_upload_accept", [
                        'رزومه',
                        $name,
                    ], "admin_file_upload_accept"), $sup->supervisor->email);
                    $notif = (new Notification("admin_file_upload_accept", [
                        'رزومه',
                        $name,
                    ]))->send($sup->supervisor->id);
                }
            }

            $send = User::sendMail(new MailVerificationCode("admin_file_upload_accept", [
                'رزومه',
                $name,
            ], "admin_file_upload_accept"), $resume->writer->email);

            session()->flash('success', 'تایید فایل با موفقیت ثبت گردید');
            return redirect()->back();
        } else {
            session()->flash('error', 'تایید فایل با شکست مواجه گردید');
            return redirect()->back();
        }
    }

    function deleteResumeFile($id, $file)
    {
        $resume = Resume::find($id);
        $files = json_decode($resume->url_uploaded_from_writer);

        $_files = [];
        foreach ($files as $key => $item) {
            if ($key !== intval($file)) {
                $_files[] = $item;
            }
        }

        $resume->url_uploaded_from_writer = $_files;

        if ($resume->save()) {
            session()->flash('success', 'حذف فایل با موفقیت ثبت گردید');
            return redirect()->back();
        } else {
            session()->flash('error', 'حذف فایل با شکست مواجه گردید');
            return redirect()->back();
        }
    }
}
