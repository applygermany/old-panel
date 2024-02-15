<?php

namespace App\Http\Services\V1\Expert;

use App\Models\Acceptance;
use App\Models\ResumeMotivationIds;
use App\Providers\SMS;
use App\Models\Category;
use App\Models\User;
use App\Models\UserComment;
use App\Models\UserSupervisor;
use App\Models\UserTelSupport;
use App\Providers\MyHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
    function getAllRM(Request $request)
    {
        $users = User::query();
        if ($request->name) {
            $users->where(function ($query) use ($request) {
                $query->orWhereRaw("LOWER(`nag_users`.`firstname`) LIKE '%" . strtolower($request->name) . "%'");
                $query->orWhereRaw("LOWER(`nag_users`.`lastname`) LIKE '%" . strtolower($request->name) . "%'");
                $query->orWhereRaw("LOWER(`nag_users`.`mobile`) LIKE '%" . MyHelpers::numberToEnglish($request->name) . "%'");
                $query->orWhereRaw("LOWER(`nag_users`.`email`) LIKE '%" . strtolower(MyHelpers::numberToEnglish($request->name)) . "%'");
            });
        }

        if ($request->category) {
            if ($request->category != 0) {
                $users = $users->where('category_id', $request->category);
            }
        }


        $supervisor = UserSupervisor::where('supervisor_id', auth()->guard('api')->id())->pluck('user_id');
        $users = $users->whereIn('id', $supervisor);

        $ids = $users->pluck('id');
        $originData = ResumeMotivationIds::orderBy('id', 'desc')->whereIn('user_id', $ids)->get();
        $dataArray = array();
        $index = 0;

        $count = 0;
        foreach ($originData as $data) {
            if ($data->data->admin_accepted_filename !== null ) {
                $count++;
            }
        }
        $superUser= User::where('id', auth()->guard('api')->id())->first();

        foreach ($originData as $data) {
            if (!$data->data->is_accepted or $superUser['level'] ==2) {
                if ($index <= $request->take) {
                    $acceptance = Acceptance::where('user_id', $data->data->user_id)->first();
                    $output = new \stdClass();
                    $output->user = $data->data->user;
                    $output->id = $data->id;
                    $output->type = $data->model_type;
                    $output->modelId = $data->model_id;
                    $output->createdAt = $data->created_at;
                    $output->acceptance = $acceptance;
                    $output->is_accepted = $data->data->is_accepted;
                    if ($data->data->admin_accepted_filename !== null or $superUser['level'] ==2) {
                        if ($data->model_type === 'resume') {
                            $resume = new \stdClass();
                            $resume = $data->data;
                            $resume->educations = $data->data->educationRecords;
                            $resume->languages = $data->data->languages;
                            $resume->works = $data->data->works;
                            $resume->softwareKnowledge = $data->data->softwareKnowledge;
                            $resume->courses = $data->data->courses;
                            $resume->researches = $data->data->researches;
                            $resume->hobbies = $data->data->hobbies;
                            $resume->extraText = $data->data->text;
                            $resume->file = $data->data->admin_accepted_filename;
                            $resume->is_accepted = $data->data->is_accepted;

                            $output->resume = $resume;
                        } else {
                            $motivation = new \stdClass();
                            $motivation = $data->data;
                            $motivation->universities = $data->data->universities;
                            $motivation->file = $data->data->admin_accepted_filename;
                            $motivation->is_accepted = $data->data->is_accepted;

                            $output->motivation = $motivation;
                        }
                        if($request->status and $request->status!='null'){
                            if($data->data->status == $request->status){
                                $dataArray[$index] = $output;
                                $index++;
                            }
                        }else{
                            $dataArray[$index] = $output;
                            $index++;
                        }

                    }
                }
            }
        }

        $categories = Category::all();

        return [$dataArray, $categories, $count, $users->count()];
    }


}