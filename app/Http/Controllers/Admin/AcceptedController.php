<?php

namespace App\Http\Controllers\Admin;

use App\Models\NewAccepted;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class AcceptedController extends Controller
{
    function accepteds()
    {
        $accepteds = NewAccepted::with('user:id,firstname,lastname,mobile')->orderBy('id', 'DESC')->paginate(10);
        $universities = University::orderBy('id', 'DESC')->get();
        $users = User::orderBy('id', 'DESC')->get();

        return view('admin.accepteds.accepteds', compact('accepteds', 'universities', 'users'));
    }

    function getAccepteds(Request $request)
    {
        $accepted = NewAccepted::Query();
        if ($request->searchName) {
            $accepted->where('name', 'LIKE', '%' . $request->searchName . '%');
        }
 
        if ($request->searchSemester) {
            $accepted->where(DB::raw("json_extract(universities, '$[0].semester')"), 'LIKE', '%' . $request->searchSemester . '%');
        }
        $accepteds = $accepted->orderBy('id', 'DESC')->paginate(10);
        return view('admin.accepteds.list', compact('accepteds'))->render();
    }

    function saveAccepted(Request $request)
    {
        $rules = [
            'name' => 'required|max:250',
            // 'photo' => 'required',
            'university.*.id' => 'required|max:250',
            'university.*.grade' => 'required|max:250',
            'university.*.field' => 'required|max:250',
            'university.*.semester' => 'required|max:250',
        ];

        $customMessages = [
            'name.required' => 'نام را وارد کنید',
            'name.max' => 'نام باید حداکثر 250 کاراکتر باشد',

            'field.required' => 'رشته را وارد کنید',
            'field.max' => 'رشته باید حداکثر 250 کاراکتر باشد',

            'language.required' => 'زبان را وارد کنید',
            'language.max' => 'زبان باید حداکثر 250 کاراکتر باشد',

            'grade.required' => 'مقطع را وارد کنید',
            'grade.max' => 'مقطع باید حداکثر 250 کاراکتر باشد',

            'university.*.*.required' => 'این فیلد ضروری است',


        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            session()->flash('error', 'خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $accepted = new NewAccepted();
        $accepted->name = $request->name;
        $accepted->name = $request->name;
        if ($request->file('video')) {
            $time  = time();
            $folder = '/uploads/newAcceptedsVideo/';
            $file = $request->file('video');
            $file->move(public_path() . $folder, $time . '.mp4');
            $accepted->video_link = route("newAcceptedsVideo", ["id" =>  $time]);
        } 
        if ($request->file('photo')) {
            $time  = time();
            $folder = '/uploads/newAcceptedsPhoto/';
            $file = $request->file('photo');
            $file->move(public_path() . $folder, $time . '.png');
            $accepted->photo = route("newAcceptedsPhoto", ["id" =>  $time]);
        } else {
            $accepted->photo = "";
        }
        $uni = $request->university;
        $index = 0;
        
        $accepted->universities = $uni;

        if ($accepted->save()) {
            if ($request->file('visaImage')) {
                $folder = '/uploads/acepted/';
                $file = $request->file('visaImage');
                $file->move(public_path() . $folder, $accepted->id . '_visa.jpg');
            }
            foreach ($uni as $item) {
                $index++;
    
                if ($item['file'] ?? "") {
                    $folder = '/uploads/acepted/';
                    $file = $item['file'];
                    $file->move(public_path() . $folder, $accepted->id . "_acceptance_{$index}.jpg");
                }
            }

            session()->flash('success', 'پذیرفته شده با موفقیت ثبت شد');
        } else
            session()->flash('error', 'خطا در ثبت پذیرفته شده');
        return redirect()->back();
    }

    function editAccepted($id)
    {
        $accepted = NewAccepted::find($id);
        $allUniversities = University::get();
        $pics = "";
        $university_section = "";
        $m = -1;
        foreach ($accepted->universities as $university) {
            $university = (object) $university;
            $m++;

            $uni_options = "";
            foreach ($allUniversities as $allUniversity) {
                if ($allUniversity->id == $university->id) {
                    $uni_options .= '<option selected value="' . $allUniversity->id . '">' . $allUniversity->title . '</option>';
                } else {
                    $uni_options .= '<option value="' . $allUniversity->id . '">' . $allUniversity->title . '</option>';
                }
            }
            $pics .= '<div class="form-group col-12 col-lg-6 text-center">
            <br>
            <img src="' . route('imageAcceptance', ['id' => $accepted->id, 'pos' => $m + 1]) . '" width="80%">
        </div>';
            $university_section .= '<div class="col-12 university-container-edit row">
            <div class="col-12 col-lg-3">
            <div class="form-group float-label">
                <label for="university[' . $m . '][id]" class="header-label">دانشگاه</label>
                <select cs="select" name="university[' . $m . '][id]" id="university[' . $m . '][id]" class="select-e-'.$m.' form-control form-select-sm " data-control="select-e-'.$m.'">
                    <option value="">انتخاب کنید</option>
                    ' . $uni_options . '
                </select>
            
            </div>
            </div>
            
            <div class="col-6 col-lg-2">
            <div class="form-group float-label">
                <label for="university[' . $m . '][field]" class="header-label">رشته</label>
                <input type="text" name="university[' . $m . '][field]" class="form-control form-control-sm " id="university[' . $m . '][field]" placeholder="رشته" value="' . $university->field . '">
            
            </div>
            </div>
            <div class="col-6 col-lg-2">
            <div class="form-group float-label">
                <label for="university[' . $m . '][grade]" class="header-label">مقطع</label>
                <input type="text" name="university[' . $m . '][grade]" class="form-control form-control-sm " id="university[' . $m . '][grade]" placeholder="مقطع" value="' . $university->grade . '">
            
            </div>
            </div>
            <div class="col-6 col-lg-2">
            <div class="form-group float-label">
                <label for="university[' . $m . '][semester]" class="header-label">ترم</label>
                <input type="text" name="university[' . $m . '][semester]" class="form-control form-control-sm " id="university[' . $m . '][semester]" placeholder="ترم" value="' . $university->semester . '">
            
            </div>
            </div>
            

            <div class="col-12 col-lg-2">
                <div class="form-group float-label">
                    <label for="university[' . $m . '][file]" class="header-label">تصویر پذیرش </label>
                    <input type="file" name="university[' . $m . '][file]" class="form-control form-control-sm" id="university[' . $m . '][file]">
                </div>
            </div>
            <div class="col-12 col-lg-1">
                <div class="form-group float-label d-flex">
                    <span>حذف تصویر پذیرش </span>
                    <input type="checkbox" name="university[' . $m . '][deleteCurrent]" class="" id="university[' . $m . '][deleteCurrent]">
                </div>
            </div>


            </div>';
        }
        $data = csrf_field() . '
<input name="editId" type="hidden" id="editId" value="' . $accepted->id . '">
<div class="row">
<div class="col-12 col-lg-3">
<div class="form-group float-label">
    <label for="name" class="header-label">نام دانشجو</label>
    <input name="name" id="name" class="form-control form-select-sm" value="' . $accepted->name . '">


</div>



</div>
<div class="col-12 col-lg-3">
<div class="form-group float-label">
    <label for="photo" class="header-label">تصویر دانشجو </label>
    <input type="file" name="photo" class="form-control form-control-sm" id="photo">
</div>
</div>
<div class="form-group col-12 col-lg-3 text-center">
            <br>
            <img src="' . $accepted->photo . '" width="80%">
        </div>
<div class="col-12 university-base-edit row">
' . $university_section . '
</div>
<div class="col-12 mt-2 mb-4">
<button type="button" class="btn btn-info btn-sm col-4 add-university-edit"><i class="fas fa-plus"></i>اضافه کردن دانشگاه</button>
</div>


<div class="col-12 col-lg-6">
<div class="form-group float-label">
<label for="visaImage" class="header-label">تصویر ویزا</label>
<input type="file" name="visaImage" class="form-control form-control-sm" id="visaImage">
</div>
</div>

<div class="col-12 col-lg-6">
<div class="form-group float-label">
    <label for="video" class="header-label"> ویدیو</label>
    <input type="file" name="video" class="form-control form-control-sm" id="video" placeholder=" ویدیو">

</div>
</div>


</div>

<div class="fw-bolder rotate collapsible" data-bs-toggle="collapse" href="#kt_customer_view_details" role="button" aria-expوed="false" aria-controls="kt_customer_view_details">
      <span class="ms-2">تصاویر آپلود شده</span>
 </div>
 <div id="kt_customer_view_details" class=" collapse row">
  <div  class="form-group col-12 col-lg-6 text-center">
        <br>
        ویزا
        <img src="' . route('imageVisa', ['id' => $accepted->id]) . '" width="80%">
    </div>
' . $pics . '
    </div>

</div>
<script>$("[cs=select]").select2()</script>
';
        return $data;
    }

    function updateAccepted(Request $request)
    {
        $rules = [
            'university.*.id' => 'required|max:250',
            'university.*.grade' => 'required|max:250',
            'university.*.field' => 'required|max:250',
            'university.*.semester' => 'required|max:250',
        ];

        $customMessages = [

            'field.required' => 'رشته را وارد کنید',
            'field.max' => 'رشته باید حداکثر 250 کاراکتر باشد',

            'language.required' => 'زبان را وارد کنید',
            'language.max' => 'زبان باید حداکثر 250 کاراکتر باشد',

            'grade.required' => 'مقطع را وارد کنید',
            'grade.max' => 'مقطع باید حداکثر 250 کاراکتر باشد',

            'university.*.*.required' => 'تمام فیلد ها الزامی است',


        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()]);

        $accepted = NewAccepted::find($request->editId);
        $accepted->name = $request->name;
        if ($request->file('video')) {
            $time  = time();
            $folder = '/uploads/newAcceptedsVideo/';
            $file = $request->file('video');
            $file->move(public_path() . $folder, $time . '.mp4');
            $accepted->video_link = route("newAcceptedsVideo", ["id" =>  $time]);
        }
        if ($request->file('photo')) {
            $time  = time();
            $folder = '/uploads/newAcceptedsPhoto/';
            $file = $request->file('photo');
            $file->move(public_path() . $folder, $time . '.png');
            $accepted->photo = route("newAcceptedsPhoto", ["id" =>  $time]);
        }
        $uni = $request->university;
        $index = 0;
        foreach ($uni as $item) {
            $index++;
            if ($item['file'] ?? "") {
                $folder = '/uploads/acepted/';
                $file = $item['file'];
                $file->move(public_path() . $folder, $accepted->id . "_acceptance_{$index}.jpg");
            }
            if ($item['deleteCurrent'] ?? "") {
                $folder = '/uploads/acepted/';
                $file = $item['file'];
                unlink(public_path('uploads/acepted/' . $accepted->id  . "_acceptance_{$index}.jpg"));
            }
        }
        $accepted->universities = $uni;
        if ($accepted->save()) {
            if ($request->file('visaImage')) {
                $folder = '/uploads/acepted/';
                $file = $request->file('visaImage');
                $file->move(public_path() . $folder, $accepted->id . '_visa.jpg');
            }


            return 1;
        }
        return 2;
    }

    function deleteAccepted($id)
    {
        $accepted = NewAccepted::find($id);
        if ($accepted->delete()) {
            if (is_file(public_path('uploads/acepted/' . $accepted->id . '_visa.jpg')))
                unlink(public_path('uploads/acepted/' . $accepted->id . '_visa.jpg'));
            if (is_file(public_path('uploads/acepted/' . $accepted->id . '_acceptance.jpg')))
                unlink(public_path('uploads/acepted/' . $accepted->id . '_acceptance.jpg'));
            session()->flash('success', 'پذیرفته شده با موفقیت حذف شد');
        } else
            session()->flash('error', 'خطا در حذف پذیرفته شده');
        return redirect()->back();
    }
}
