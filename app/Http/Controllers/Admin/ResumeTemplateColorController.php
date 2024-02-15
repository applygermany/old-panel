<?php

namespace App\Http\Controllers\Admin;

use App\Models\Motivation;
use App\Models\MotivationUniversity;
use App\Models\ResumeTemplate;
use App\Models\ResumeCourse;
use App\Models\ResumeEducationRecord;
use App\Models\ResumeHobby;
use App\Models\ResumeLanguage;
use App\Models\ResumeResearch;
use App\Models\ResumeSoftwareKnowledge;
use App\Models\ResumeTemplateColor;
use App\Models\ResumeWork;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class ResumeTemplateColorController extends Controller
{
    function resumeTemplateColors()
    {
        $resumeTemplateColors = ResumeTemplateColor::orderBy('id', 'DESC')->paginate(10);
        return view('admin.resumeTemplateColors.colors', compact('resumeTemplateColors'));
    }
    
    function getResumeTemplateColors(Request $request)
    {
        $resume = ResumeTemplateColor::Query();
        if ($request->searchCode)
            $resume->where('code', "like", "%".  $request->searchCode . "%");
        if ($request->searchName)
            $resume->where('title', "like", "%". $request->searchName . "%");
       
        $resumeTemplateColors = $resume->orderBy('id', 'DESC')->paginate(10);
        return view('admin.resumeTemplateColors.list', compact('resumeTemplateColors'))->render();
    }


   
    function saveColor(Request $request)
    {

  
            $rules =  [
                'name' => 'required',
                 'code' => 'required|min:6|max:6',
            ];
       
        $validator = validator()->make($request->all(), $rules);
        $first_error = "";
        $errors = $validator->errors();

        if ($validator->fails()) {
            foreach ($errors->toArray() as $key => $error) {
                $first_error = $error[0];
                session()->flash("error", $first_error);
                return redirect()->back();
            }
        }
        if(ResumeTemplateColor::where("code", $request->code)->first()){
              session()->flash("error", "این کد موجود است");
                return redirect()->back();
        }

     
            $resume_color = new ResumeTemplateColor();

            $resume_color->title = $request->name;
            $resume_color->code = $request->code;
           
            if (!$resume_color->save()) {
               session()->flash("error", "خطای دیتابیس");
                return redirect()->back();
            }
      


        session()->flash("success", "انجام شد");
        return redirect()->back();
    }



}
