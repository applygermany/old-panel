<?php
namespace App\Http\Controllers\Admin;

use App\Models\ApplyLevel;
use App\Models\ApplyPhase;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ApplyPhaseController extends Controller
{
    function applyPhases() {
        $applyPhases = ApplyPhase::orderBy('id','DESC')->paginate(10);
        return view('admin.applyPhases.applyPhases',compact('applyPhases'));
    }




    function editApplyPhase($id) {
        $applyPhase = ApplyPhase::find($id);
        return view('admin.applyPhases.edit',compact('applyPhase'));
    }

    function updateApplyPhase (Request $request) {
        $rules = [
            'title'=>'required|max:200',
            'description'=>'required|max:200',
            
        ];

        $customMessages = [
            'title.required' => 'ورود عنوان الزامی است',
            'title.max' => 'عنوان حداکثر باید 200 کاراکتر باشد',

            'description.required' => ' توضیحات الزامی است',
            'title.max' => 'توضیحات حداکثر باید 200 کاراکتر باشد',

           
        ];

        $validator = validator()->make($request->all(),$rules,$customMessages);

        if ($validator->fails())
        {
            session()->flash('error','خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $applyPhase = ApplyPhase::find($request->id);
        $applyPhase->title = $request->title;
        $applyPhase->description = $request->description;
        if($applyPhase->save()){
            session()->flash('success','انجام شد');
            return redirect()->back();
        }
        session()->flash('error','خطا در ویرایش مرحله اپلای');
        return redirect()->back();
    }

    function deleteApplyLevel($id) {
        $applyLevel = ApplyLevel::find($id);
        if($applyLevel->delete) {
            if (is_file(public_path('uploads/level/' . $applyLevel->id . '.jpg')))
                unlink(public_path('uploads/level/' . $applyLevel->id . '.jpg'));
            $files = glob(public_path('uploads/level-file/'.$id.'/.*'));
            if(count($files) > 0) {
                if (is_file($files[0]))
                    unlink($files[0]);
            }
            session()->flash('success','مرحله اپلای با موفقیت حذف شد');
        }
        else
            session()->flash('error','خطا در حذف مرحله اپلای');
        return redirect()->back();
    }
}
