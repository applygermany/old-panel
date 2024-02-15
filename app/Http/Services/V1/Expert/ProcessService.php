<?php

namespace App\Http\Services\V1\Expert;

use App\Models\Category;
use App\Models\User;
use App\Models\UserProcess;
use App\Providers\MyHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcessService
{
    public function getProcesses(Request $request)
    {
        $experts = User::whereIn('level',[2,3])->select('id','firstname')->get();
        $categories = Category::all();
        $request->name = MyHelpers::numberToEnglish($request->name);
        $select = "select nag_acceptances.*,
nag_users.id AS UID,nag_users.category_id,nag_users.firstname,nag_users.lastname,nag_user_supervisors.supervisor_id AS supervisorID
from nag_acceptances
inner join nag_users ON nag_acceptances.user_id = nag_users.id
inner join nag_user_processes ON nag_acceptances.user_id = nag_user_processes.user_id
inner join nag_user_supervisors ON nag_users.id = nag_user_supervisors.user_id";
        $whereCondition = 0;
        if($request->name) {
            if($whereCondition == 0)
                $select .= " where (UPPER(nag_users.firstname) LIKE UPPER('%".$request->name."%')";
            else
                $select .= " and (UPPER(nag_users.firstname) LIKE UPPER('%".$request->name."%')";
            $select .= " or UPPER(nag_users.lastname) LIKE UPPER('%".$request->name."%')";
            $select .= " or nag_users.email LIKE '%".strtolower($request->name)."%'";
            $select .= " or nag_users.mobile LIKE '%".$request->name."%')";
            $whereCondition = 1;
        }
        if($request->expert) {
            if($request->expert != 0) {
                if($whereCondition == 0)
                    $select .= " where nag_user_supervisors.supervisor_id = ".$request->expert;
                else
                    $select .= " and nag_user_supervisors.supervisor_id = ".$request->expert;
                $whereCondition = 1;
            }
        }
        if($request->category) {
            if($request->category != 0) {
                if($whereCondition == 0)
                    $select .= " where nag_users.category_id = ".$request->category;
                else
                    $select .= " and nag_users.category_id = ".$request->category;
            }
        }

        $select .= " group by UID";

        if($request->order) {
            if($request->order == 1)
                $select .= " order by nag_users.category_id desc";
            if($request->order == 2)
                $select .= " order by nag_user_processes.progress desc";
        } else {
            $select .= " order by nag_users.id desc";
        }

        $users = DB::connection('mysql2')->select($select.' limit 10');
        return ['users'=>$users,'experts'=>$experts,'categories'=>$categories];
    }

    public function updateProcess(Request $request)
    {
        $logged_user = auth()->guard('api')->user();
        if (!$logged_user->users()->where('user_id' , $request->userId)->first())
            return 0;
        $user = User::find($request->userId);
        $acceptance = $user->acceptances()->first();
        $userProcess = UserProcess::where('user_id',$request->userId)->first();
        $userProcess->last_tracking = $request->last_tracking;
        $userProcess->next_tracking = $request->next_tracking;
        $userProcess->text = $request->text;
        $userProcess->next_step = $request->next_step;

        if ($acceptance->admittance == 'ارشد') {
            $acceptance->field_license = $request->field_license;
            $acceptance->average_license = $request->average_license;
        } else {
            $acceptance->field_grade = $request->field_grade;
            $acceptance->diploma_grade_average = $request->diploma_grade_average;
        }
        $acceptance->what_grade_language = $request->what_grade_language;

        if($user->mobile == null)
            $acceptance->phone = $request->phone;

        if($request->contract_sent != null) {
            $userProcess->contract_sent = $request->contract_sent;
            if($request->contract_sent == 0)
                $userProcess->progress = $userProcess->progress-1;
            else
                $userProcess->progress = $userProcess->progress+1;
        }

        if($request->contract_sign != null) {
            $userProcess->contract_sign = $request->contract_sign;
            if($request->contract_sign == 0)
                $userProcess->progress = $userProcess->progress-1;
            else
                $userProcess->progress = $userProcess->progress+1;
        }

        if($request->language_degree != null) {
            $userProcess->language_degree = $request->language_degree;
            if($request->language_degree == 0)
                $userProcess->progress = $userProcess->progress-1;
            else
                $userProcess->progress = $userProcess->progress+1;
        }

        if($request->translate != null) {
            $userProcess->translate = $request->translate;
            if($request->translate == 0)
                $userProcess->progress = $userProcess->progress-1;
            else
                $userProcess->progress = $userProcess->progress+1;
        }

        if($request->embassy_approve != null) {
            $userProcess->embassy_approve = $request->embassy_approve;
            if($request->embassy_approve == 0)
                $userProcess->progress = $userProcess->progress-1;
            else
                $userProcess->progress = $userProcess->progress+1;
        }

        if($request->document_upload != null) {
            $userProcess->document_upload = $request->document_upload;
            if($request->document_upload == 0)
                $userProcess->progress = $userProcess->progress-1;
            else
                $userProcess->progress = $userProcess->progress+1;
        }

        if($request->document_check != null) {
            $userProcess->document_check = $request->document_check;
            if($request->document_check == 0)
                $userProcess->progress = $userProcess->progress-1;
            else
                $userProcess->progress = $userProcess->progress+1;
        }

        if($request->resume != null) {
            $userProcess->resume = $request->resume;
            if($request->resume == 0)
                $userProcess->progress = $userProcess->progress-1;
            else
                $userProcess->progress = $userProcess->progress+1;
        }

        if($request->motivation != null) {
            $userProcess->motivation = $request->motivation;
            if($request->motivation == 0)
                $userProcess->progress = $userProcess->progress-1;
            else
                $userProcess->progress = $userProcess->progress+1;
        }

        if($request->university_list != null) {
            $userProcess->university_list = $request->university_list;
            if($request->university_list == 0)
                $userProcess->progress = $userProcess->progress-1;
            else
                $userProcess->progress = $userProcess->progress+1;
        }

        if($request->document_post != null) {
            $userProcess->document_post = $request->document_post;
            if($request->document_post == 0)
                $userProcess->progress = $userProcess->progress-1;
            else
                $userProcess->progress = $userProcess->progress+1;
        }

        if($request->purify != null) {
            $userProcess->purify = $request->purify;
            if($request->purify == 0)
                $userProcess->progress = $userProcess->progress-1;
            else
                $userProcess->progress = $userProcess->progress+1;
        }

        if($userProcess->save() && $acceptance->save())
            return 1;
        return 0;
    }

    public function updateProcessUniversity(Request $request)
    {
        $logged_user = auth()->guard('api')->user();
        if (!$logged_user->users()->where('user_id', $request->userId)->first())
            return 0;
        $user = User::find($request->userId);
        $university = $user->universities()->find($request->universityId);
        if($university) {
            $university->deadline = $request->deadline;
            $university->save();
            return 1;
        }
        return 0;
    }
}
