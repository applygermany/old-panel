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
use App\Models\ResumeWork;
use App\Models\University;
use App\Models\User;
use App\Models\ResumeTemplateColor;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;

class ResumeTemplateController extends Controller {
	function resumeTemplates() {
		$colors = ResumeTemplateColor::orderBy('id', 'DESC')->get();
		$resumeTemplates = ResumeTemplate::orderBy('id', 'DESC')->paginate(10);
		return view('admin.resumeTemplates.templates', compact('resumeTemplates', 'colors'));
	}
	
	function saveResumeTemplate(Request $request) {
		$rules = [
			'name'  => 'required',
			'image' => 'required|max:10000',
		];
		$customMessages = [
			'image.required' => 'فایل را انتخاب کنید',
			'image.max'      => 'حجم فایل باید کمتر از 10 مگابایت باشد',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails() ) {
			session()->flash("error", "تمام فیلدها ضروری است");
			return redirect()->back();
		}
		if ($request->color == NULL){
			$request->color = [];
		}
		$resumeTemp = new ResumeTemplate();
		$resumeTemp->name = $request->name;
		$colors = [];
		foreach ($request->color as $key => $value) {
			$colors[] = "#" . $key;
		}
		$resumeTemp->colors = $colors;
		$resumeTemp->image = "";
		$resumeTemp->save();
		if ($request->file('image')) {
			$folder = '/uploads/templateImage/';
			$file = $request->file('image');
			$file->move(public_path() . $folder, $resumeTemp->id . '.jpg');
			$resumeTemp->image = route('templateImage', ["id" => $resumeTemp->id]);
			$resumeTemp->save();
		}
		session()->flash("success", "با موفقیت ثبت شد");
		return redirect()->back();
	}
	
	
	function getResumeTemplates(Request $request) {
		$colors = ResumeTemplateColor::orderBy('id', 'DESC')->get();
		$resume = ResumeTemplate::Query();
		if ($request->searchCode)
			$resume->where('id', $request->searchCode);
		if ($request->searchName)
			$resume->where('name', "like", "%" . $request->searchName . "%");
		$resumeTemplates = $resume->orderBy('id', 'DESC')->paginate(10);
		return view('admin.resumeTemplates.list', compact('resumeTemplates', 'colors'))->render();
	}
	
	function deleteResumeTemplate($id) {
		$resume = ResumeTemplate::find($id);
		if ($resume->delete())
			session()->flash('success', 'قالب حذف شد');
		else
			session()->flash('error', 'خطا در حذف قالب رزومه');
		return redirect()->back();
	}
	
	function editResumeTemplate($id) {
		$resume = ResumeTemplate::where("id", $id)->first();
		$records = "";
		$colors = ResumeTemplateColor::orderBy('id', 'DESC')->get();
		foreach ($colors as $color) {
			$records .= '<div class="col-6 col-md-2">
                                        <div class="form-group">
                                            <label for="color[' . $color->id . ']" class="header-label">' . $color->title . '<div style="width:20px;height:20px;background:#' . $color->code . '"></div> </label>
                                            
                                            <input type="checkbox" ' . (in_array($color->code, $resume->colors) ? 'checked' : '') . ' name="color[' . $color->code . ']" class="" id="color[' . $color->code . ']">
                                        </div>
                                        </div>';
		}
		$data = csrf_field() . '<input name="editId" type="hidden" value="' . $resume->id . '"><div class="row">
        
  
                                    <div class="col-12 col-md-6">
                                        <div class="form-group float-label">
                                            <label for="name" class="header-label">نام</label>
                                            <input type="text" name="name" class="form-control form-control-sm " id="name" placeholder="نام" value="' . $resume->name . '">
                                           
                                        </div>
                                    </div>
                                  
                                    <div class="col-12 col-md-6">
                                        <div class="form-group float-label">
                                            <label for="image" class="header-label">تصویر</label>
                                            <input type="file" name="image" class="form-control form-control-sm" id="image">
                                        </div>
                               </div>
                                    
                                    ' . $records . '
                                    </div>       <div class="mt-3 ">
                                        <img src="' . route('templateImage', ["id" => $resume->id]) . '" alt="image">
                                    </div></div>';
		return $data;
	}
	
	function updateResumeTemplates(Request $request) {
		
		$rules = [
			'name'  => 'required',
//			'image' => 'required|max:10000',
		];
		$customMessages = [
//			'image.required' => 'فایل را انتخاب کنید',
//			'image.max'      => 'حجم فایل باید کمتر از 10 مگابایت باشد',
		];
		$validator = validator()->make($request->all(), $rules, $customMessages);
		if ($validator->fails() || $request->color == NULL) {
			return response()->json(['errors' => [], "msg" => "تمام فیلدها ضروری است", "type" => "warning"]);
		}
		$resumeTemp = ResumeTemplate::find($request->editId);
		$resumeTemp->name = $request->name;
		$colors = [];
		foreach ($request->color as $key => $value) {
			$colors[] = "#" . $key;
		}
		$resumeTemp->colors = $colors;
		if ($request->file('image')) {
			$folder = '/uploads/templateImage/';
			$file = $request->file('image');
			$file->move(public_path() . $folder, $resumeTemp->id . '.jpg');
			$resumeTemp->image = route('templateImage', ["id" => $resumeTemp->id]);
			if ($resumeTemp->save()) {
				return response()->json(['errors' => [], "msg" => "انجام شد", "type" => "success"]);
			}
		} else {
			if ($resumeTemp->save()) {
				return response()->json(['errors' => [], "msg" => "انجام شد", "type" => "success"]);
			}
		}
		return response()->json(['errors' => [], "msg" => "خطای دیتابیس", "type" => "warning"]);
	}
	
	
}
