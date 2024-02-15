<?php

namespace App\Http\Services\V1\Writer;

use App\Models\Acceptance;
use App\Models\Category;
use App\Models\Motivation;
use App\Models\Resume;
use App\Models\ResumeMotivationIds;
use App\Models\User;
use App\Models\UserSupervisor;
use App\Providers\MyHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
//    function getAllRM(Request $request)
//    {
//        $users = User::query();
//        if ($request->name) {
//            $users->where(function ($query) use ($request) {
//                $query->orWhereRaw("LOWER(`nag_users`.`firstname`) LIKE '%" . strtolower($request->name) . "%'");
//                $query->orWhereRaw("LOWER(`nag_users`.`lastname`) LIKE '%" . strtolower($request->name) . "%'");
//                $query->orWhereRaw("LOWER(`nag_users`.`mobile`) LIKE '%" . MyHelpers::numberToEnglish($request->name) . "%'");
//                $query->orWhereRaw("LOWER(`nag_users`.`email`) LIKE '%" . strtolower(MyHelpers::numberToEnglish($request->name)) . "%'");
//            });
//        }
//
//        if ($request->category) {
//            if ($request->category != 0) {
//                $users = $users->where('category_id', $request->category);
//            }
//        }
//
//        $supervisor = UserSupervisor::where('supervisor_id', auth()->guard('api')->id())->pluck('user_id');
//        $users = $users->whereIn('id', $supervisor);
//        $ids = $users->pluck('id');
//
//        $resumes = Resume::where('writer_id', \auth()->guard('api')->id())->pluck('id');
//        $motivations = Motivation::where('writer_id', \auth()->guard('api')->id())->pluck('id');
//        $mergeIds = $resumes->merge($motivations);
//
//        $originData = ResumeMotivationIds::orderBy('id', 'desc')
//            ->whereIn('user_id', $ids)
//            ->whereIn('model_id', $mergeIds)
//            ->get();
//
//        $dataArray = array();
//        $index = 0;
//
//        foreach ($originData as $data) {
//            if ($index <= $request->take) {
//                $acceptance = Acceptance::where('user_id', $data->data->user_id)->first();
//                $output = new \stdClass();
//                $output->user = $data->data->user;
//                $output->id = $data->id;
//                $output->type = $data->model_type;
//                $output->createdAt = $data->created_at;
//                $output->acceptance = $acceptance;
//                if ($data->model_type === 'resume') {
//                    $resume = new \stdClass();
//                    $resume = $data->data;
//                    $resume->educations = $data->data->educationRecords;
//                    $resume->languages = $data->data->languages;
//                    $resume->works = $data->data->works;
//                    $resume->softwareKnowledge = $data->data->softwareKnowledge;
//                    $resume->courses = $data->data->courses;
//                    $resume->researches = $data->data->researches;
//                    $resume->hobbies = $data->data->hobbies;
//                    $resume->extraText = $data->data->text;
//
//                    $output->resume = $resume;
//                } elseif ($data->model_type === 'motivation') {
//                    $motivation = new \stdClass();
//                    $motivation = $data->data;
//                    $motivation->universities = $data->data->universities;
//
//                    $output->motivation = $motivation;
//                }
//                $dataArray[$index] = $output;
//                $index++;
//            }
//        }
//
//        $categories = Category::all();
//
//        return [$dataArray, $categories, $originData->count()];
//    }

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

        $resumes = Resume::where('writer_id', \auth()->guard('api')->id())->pluck('id');

        $motivations = Motivation::where('writer_id', \auth()->guard('api')->id())->pluck('id');
        $mergeIds = $resumes->merge($motivations);

        $originData = ResumeMotivationIds::orderBy('id', 'desc')
            ->whereIn('user_id', $ids)
            ->whereIn('model_id', $mergeIds)
            ->get();

        $dataArray = array();
        $index = 0;

        foreach ($originData as $data) {
            if ($index <= $request->take) {

                $acceptance = Acceptance::where('user_id', $data->data->user_id)->first();
                $output = new \stdClass();
                $output->user = $data->data->user;
                $supervisors= $data->data->user->supervisors;
                $output->userSupervisor=['supervisor'=>null,'support'=>null];
                foreach ($supervisors as $supervisor){
                    if($supervisor->supervisor->level==5){//karshenas
                        $output->userSupervisor['supervisor']=$supervisor->supervisor;
                    }elseif ($supervisor->supervisor->level==2){//poshtiban
                        $output->userSupervisor['support']=$supervisor->supervisor;
                    }
                }
                $output->id = $data->id;
                $output->type = $data->model_type;
                $output->createdAt = $data->created_at;
                $output->acceptance = $acceptance;
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

                    $output->resume = $resume;
                } elseif ($data->model_type === 'motivation') {
                    $motivation = new \stdClass();
                    $motivation = $data->data;
                    $motivation->universities = $data->data->universities;

                    $output->motivation = $motivation;
                    $hasMotivation=ResumeMotivationIds::where([['user_id',$motivation->user_id],['model_type','resume']])->get();
                    $newMotives=[];
                    foreach ($hasMotivation as $has){
                        array_push($newMotives,$has->data);
                    }
                    $output->resumesx = $newMotives;
                }
                $dataArray[$index] = $output;
                $index++;
            }
        }

        $categories = Category::all();

        return [$dataArray, $categories, $originData->count()];
    }
    public function changeDarkMode()
    {
        if (auth()->guard('api')->user()->darkmode == 1)
            auth()->guard('api')->user()->darkmode = 0;
        else
            auth()->guard('api')->user()->darkmode = 1;
        auth()->guard('api')->user()->save();
        return 1;
    }
}