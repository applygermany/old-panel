<?php

namespace App\Http\Services\V1\User;

use App\Models\User;
use App\Mail\MailVerificationCode;
use App\Models\Acceptance;
use App\Models\UserDuty;
use App\Models\UserSupervisor;
use App\Providers\JDF;
use App\Providers\Notification;
use App\Providers\SMS;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AcceptanceService
{
    public function acceptance()
    {
        $acceptance = auth()->guard('api')->user()->acceptances()->first();
        if (!$acceptance) {
            $acceptance = new Acceptance();
            $acceptance->user_id = auth()->guard('api')->id();
            $acceptance->last_form_submit = 0;
            $acceptance->save();
        }

        $acceptance->firstname = $acceptance->firstname ?: auth()->guard('api')->user()->firstname;
        $acceptance->lastname = $acceptance->lastname ?: auth()->guard('api')->user()->lastname;
        //$acceptance->birth_date = $acceptance->birth_date ?: auth()->guard('api')->user()->birth_date;
        $acceptance->mellicode = $acceptance->mellicode ?: auth()->guard('api')->user()->codemelli;
        $acceptance->phone = $acceptance->phone ?: auth()->guard('api')->user()->mobile;
        $acceptance->fatherName = $acceptance->fatherName ?: auth()->guard('api')->user()->father_name;
        $acceptance->email = $acceptance->email ?: auth()->guard('api')->user()->email;

        return $acceptance;
    }

    public function submitStep1(Request $request)
    {
        $acceptance = auth()->guard('api')->user()->acceptances()->first();
        if (!$acceptance)
            return 0;
        $user = User::find(auth()->guard('api')->user()->id);
        $user->father_name = $request->fatherName;
        //$user->birth_date = $request->birth_date;
        $user->codemelli = $request->mellicode;
        if (!$user->mobile)
            $user->mobile = $request->phone;
        if (!$user->email)
            $user->email = $request->email;
        $user->save();
        $acceptance->firstname = $request->firstname;
        $acceptance->lastname = $request->lastname;
        $acceptance->phone = $request->phone;
        $acceptance->mellicode = $request->mellicode;
        $acceptance->birth_date = $request->birth_date;
        $acceptance->city = $request->city;
        $acceptance->address = $request->address;
        $acceptance->fatherName = $request->fatherName;
        $acceptance->email = $request->email;
        if ($acceptance->save())
            return 1;
        return 0;
    }

    public function submitStep2(Request $request)
    {
        $acceptance = auth()->guard('api')->user()->acceptances()->first();
        if (!$acceptance)
            return 0;
        $acceptance->admittance = $request->admittance;
        $acceptance->field_grade = $request->field_grade;
        $acceptance->diploma_grade_average = $request->diploma_grade_average;
        $acceptance->pre_university_grade_average = $request->pre_university_grade_average;
        $acceptance->pre_university_field = $request->pre_university_field;
        $acceptance->is_entrance_exam = $request->is_entrance_exam;
        if ($acceptance->save())
            return 1;
        return 0;
    }

    public function submitContinueCollege(Request $request)
    {
        $acceptance = auth()->guard('api')->user()->acceptances()->first();
        if (!$acceptance)
            return 0;
        $acceptance->is_entrance_exam = $request->is_entrance_exam;
        if ($acceptance->save())
            return 1;
        return 0;
    }

    public function removeAcceptanceAfter24H()
    {
        $acceptances = Acceptance::where("last_form_submit", 0)->get();
        foreach ($acceptances as $acceptance) {
            if (time() - strtotime($acceptance->created_at) >= 86400) {
                $acceptance->delete();
            }
        }
        return 0;
    }

    public function submitStepBachelor(Request $request)
    {
        $acceptance = auth()->guard('api')->user()->acceptances()->first();
        if (!$acceptance)
            return 0;
        $acceptance->is_license_semesters = $request->is_license_semesters;
        $acceptance->license_graduated = $request->license_graduated;
        $acceptance->Pass_30_units = $request->Pass_30_units;
        $acceptance->field_license = $request->field_license;
        $acceptance->university_license = $request->university_license;
        $acceptance->average_license = $request->average_license;
        $acceptance->year_license = $request->year_license;
        $acceptance->total_number_passes = $request->total_number_passes;
        $acceptance->predicted_year_license = $request->predicted_year_license;

        if ($acceptance->save())
            return 1;
        return 0;
    }

    public function submitStepMaster(Request $request)
    {
        $acceptance = auth()->guard('api')->user()->acceptances()->first();
        if (!$acceptance)
            return 0;
        $acceptance->license_graduated = $request->license_graduated;
        $acceptance->license_type = $request->license_type;
        $acceptance->associate_diploma_field = $request->associate_diploma_field;
        $acceptance->associate_diploma_university = $request->associate_diploma_university;
        $acceptance->associate_diploma_grade_average = $request->associate_diploma_grade_average;
        $acceptance->year_associate_diploma = $request->year_associate_diploma;
        $acceptance->field_license = $request->field_license;
        $acceptance->university_license = $request->university_license;
        $acceptance->average_license = $request->average_license;
        $acceptance->average_now_license = $request->average_now_license;
        $acceptance->predicted_year_license = $request->predicted_year_license;
        $acceptance->year_license = $request->year_license;
        $acceptance->total_number_passes = $request->total_number_passes;
        $acceptance->senior_educate = 'خیر';
        if ($acceptance->save())
            return 1;
        return 0;
    }

    public function submitMasterContinue(Request $request)
    {
        $acceptance = auth()->guard('api')->user()->acceptances()->first();
        if (!$acceptance)
            return 0;
        $acceptance->field_senior = $request->field_senior;
        $acceptance->university_senior = $request->university_senior;
        $acceptance->average_senior = $request->average_senior;
        $acceptance->year_senior = $request->year_senior;
        $acceptance->master_graduated = $request->master_graduated;
        $acceptance->senior_educate = $request->senior_educate;
        $acceptance->another_educate = $request->another_educate;
        $acceptance->master_total_number_passes = $request->master_total_number_passes;
        $acceptance->average_now_master = $request->average_now_master;
        $acceptance->predicted_year_master = $request->predicted_year_master;
        if ($acceptance->save())
            return 1;
        return 0;
    }

    public function submitStep3(Request $request)
    {
        $acceptance = auth()->guard('api')->user()->acceptances()->first();
        if (!$acceptance)
            return 0;
        $acceptance->military_service = $request->military_service;
        if ($acceptance->save())
            return 1;
        return 0;
    }

    public function submitStep4(Request $request)
    {
        $acceptance = auth()->guard('api')->user()->acceptances()->first();
        if (!$acceptance)
            return 0;
        $acceptance->language_favor = $request->language_favor;
        $acceptance->license_language = $request->license_language;
        $acceptance->what_grade_language = $request->what_grade_language;
        $acceptance->date_get_grade_language = $request->date_get_grade_language;
        $acceptance->what_intent_grade_language = $request->what_intent_grade_language;
        $acceptance->score_grade_language = $request->score_grade_language;
        $acceptance->date_intent_grade_language = $request->date_intent_grade_language;
        $acceptance->current_language_status = $request->current_language_status;
        if ($acceptance->save())
            return 1;
        return 0;
    }

    public function submitStep5(Request $request)
    {
        $acceptance = auth()->guard('api')->user()->acceptances()->first();
        if (!$acceptance)
            return 0;
        $acceptance->doc_translate = $request->doc_translate;
        $acceptance->doc_translate_year_passed = $request->doc_translate_year_passed;
        $acceptance->doc_embassy = $request->doc_embassy;
        if ($acceptance->save())
            return 1;
        return 0;
    }

    public function submitStep6(Request $request)
    {
        $acceptance = auth()->guard('api')->user()->acceptances()->first();
        if (!$acceptance)
            return 0;
        DB::beginTransaction();
        try {
            $acceptance->description = $request->description;
            $acceptance->last_form_submit = 1;
            $acceptance->created_at = date('Y-m-d H:i:s');
            $acceptance->apply_percent = $request->applyPercent ?? 0;
            $acceptance->apply_time = $request->timeStatus ?? '';
            $acceptance->save();

            auth()->guard('api')->user()->type = $request->packageId;
            auth()->guard('api')->user()->save();

            $userSsupervisor = new UserSupervisor();
            $userSsupervisor->user_id = auth()->guard('api')->id();
            $userSsupervisor->supervisor_id = 26;
            $userSsupervisor->save();

            $user = $acceptance->user;

            $user->max_university_count = $user->type === 2 ? 8 : 6;


            if ($request->applyPercent >= 60 && ($request->timeStatus === 'کمتر از 6 ماه' || $request->timeStatus === 'تا 1 سال آینده')) {
                $user->sup_level = 'one';
            }elseif ($request->applyPercent < 30 && ($request->timeStatus === 'کمتر از 6 ماه' || $request->timeStatus === 'تا 1 سال آینده')) {
                $user->sup_level = 'two';
            }elseif (($request->applyPercent >= 30 || $request->applyPercent < 60) && ($request->timeStatus === 'کمتر از 6 ماه' || $request->timeStatus === 'تا 1 سال آینده')) {
                $user->sup_level = 'one';
            }elseif ($request->applyPercent <= 50 && ($request->timeStatus === '1 تا 2 سال اینده' || $request->timeStatus === 'بیش از 2 سال')) {
                $user->sup_level = 'two';
            }elseif ($request->applyPercent >= 60 && ($request->timeStatus === '1 تا 2 سال اینده' || $request->timeStatus === 'بیش از 2 سال')) {
                $user->sup_level = 'two';
            }

            $user->save();


            $name = $user->firstname . " " . $user->lastname;
            $send = (new SMS())->sendVerification($user->mobile, "new_acceptance", "name=={$name}");
            $send = User::sendMail(new MailVerificationCode("new_acceptance", [$name], "new_acceptance"), $user->email);
            $notif = (new Notification("new_acceptance", [$name]))->send($user->id);

            /**
             * Add rows to didar users table
             */
            /**
             * Add rows to didar users table
             */
            $didarService = new DidarService();
            $didarService->saveToTable('MobilePhone', $user->mobile, $user->id);
            $didarService->saveToTable('Field_996_0_22', $acceptance->birth_date, $user->id);
            $didarService->saveToTable('Field_996_0_7', $acceptance->city, $user->id);
            $didarService->saveToTable('Field_996_4_9', $acceptance->admittance, $user->id);
            $didarService->saveToTable('Field_996_0_8', $acceptance->field_license, $user->id);
            $didarService->saveToTable('Field_996_0_10', $acceptance->average_license, $user->id);
            $didarService->saveToTable('Field_996_4_11', $acceptance->language_favor, $user->id);
            $didarService->saveToTable('Field_996_4_18', $user->type === 2 ? "ویژه" : ($user->type === 3 ? "پایه" : "عادی"), $user->id);
            $didarService->saveToTable('owner-id', '0ca60420-d957-4822-8bd7-b044eac00f0b', $user->id);

            $didarService->updateDidarApi($user->id);

            /**
             * Update deal
             */
            if ($didarService->getData($user->id, 'deal_id')) {
                $didarService->updateDeal($user->id, "ed3a823b-81f5-4513-95ab-496c7a9a03eb");
            } else {
                $didarService->addDeal($user->id, "ed3a823b-81f5-4513-95ab-496c7a9a03eb");
            }
            DB::commit();
            return 1;
        } catch (\Exeption $e) {
            DB::rollBack();
        }
        return 0;
    }
}
