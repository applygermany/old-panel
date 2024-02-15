<?php

namespace App\Http\Controllers\Admin;

use App\Models\University;
use App\Models\UserUniversity;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UniversityController extends Controller
{
    function universities()
    {
        $universities = University::orderBy('id', 'DESC')->paginate(10);
        return view('admin.universities.universities', compact('universities'));
    }

    function getUniversities(Request $request)
    {
        $university = University::Query();
        if ($request->searchTitle)
            $university->where('title', 'LIKE', '%' . $request->searchTitle . '%');
        if ($request->searchCity)
            $university->where('city', 'LIKE', '%' . $request->searchCity . '%');
        if ($request->searchState)
            $university->where('state', 'LIKE', '%' . $request->searchState . '%');
        if ($request->searchGeo && $request->searchGeo != "-1")
            $university->where('geographical_location', 'LIKE', $request->searchGeo);
        if ($request->searchCost && $request->searchCost != "-1")
            $university->where('cost_living', 'LIKE', $request->searchCost);
        $universities = $university->orderBy('id', 'DESC')->paginate(10);
        return view('admin.universities.list', compact('universities'))->render();
    }

    function saveUniversity(Request $request)
    {
        $rules = [
            'title' => 'required|max:200|bad_chars',
            'city' => 'required|max:200|bad_chars',
            'state' => 'required|max:200|bad_chars',
            'geographicalLocation' => 'required|max:200|bad_chars',
            'cityCrowd' => 'required|should_be_nums',
            'costLiving' => 'required|should_be_nums',
            'image' => 'required|image',
            'logo' => 'required|image',
            'logo_acceptance' => 'required|image',
        ];

        $customMessages = [
            'title.required' => 'ورود عنوان الزامی است',
            'title.max' => 'عنوان حداکثر باید 200 کاراکتر باشد',
            'title.bad_chars' => 'عنوان حاوی کاراکتر های غیر مجاز است',

            'city.required' => 'ورود شهر الزامی است',
            'city.max' => 'شهر حداکثر باید 200 کاراکتر باشد',
            'city.bad_chars' => 'شهر حاوی کاراکتر های غیر مجاز است',

            'state.required' => 'ورود استان الزامی است',
            'state.max' => 'استان حداکثر باید 200 کاراکتر باشد',
            'state.bad_chars' => 'استان حاوی کاراکتر های غیر مجاز است',

            'geographicalLocation.required' => 'ورود مکان جغرافیایی الزامی است',
            'geographicalLocation.max' => 'مکان جغرافیایی حداکثر باید 200 کاراکتر باشد',
            'geographicalLocation.bad_chars' => 'مکان جغرافیایی حاوی کاراکتر های غیر مجاز است',

            'cityCrowd.required' => 'جمعیت شهر حاوی کاراکتر های غیر مجاز است',
            'cityCrowd.should_be_nums' => 'جمعیت شهر فقط می تواند عدد باشد',

            'costLiving.should_be_nums' => 'هزینه زندگی فقط می تواند عدد باشد',
            'costLiving.required' => 'ورود هزینه زندگی الزامی است',

            'image.required' => 'این فیلد الزامی است',
            'logo.required' => 'این فیلد الزامی است',
            'logo_acceptance.required' => 'این فیلد الزامی است',

            'image.image' => 'ورودی باید عکس باشد',
            'logo.image' => 'ورودی باید عکس باشد',
            'logo_acceptance.image' => 'ورودی باید عکس باشد',

        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            session()->flash('error', 'خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $university = new University();
        $university->title = $request->title;
        $university->city = $request->city;
        $university->state = $request->state;
        $university->geographical_location = $request->geographicalLocation;
        $university->city_crowd = $request->cityCrowd;
        $university->cost_living = $request->costLiving;
        if ($university->save()) {
            if ($request->file('image')) {
                $folder = '/uploads/university/';
                $file = $request->file('image');
                $file->move(public_path() . $folder, $university->id . '.jpg');
            }
            if ($request->file('logo')) {
                $folder = '/uploads/university/';
                $file = $request->file('logo');
                $file->move(public_path() . $folder, $university->id . '_logo.jpg');
            }
            if ($request->file('logo_acceptance')) {
                $folder = '/uploads/university/';
                $file = $request->file('logo_acceptance');
                $file->move(public_path() . $folder, $university->id . '_acceptance_logo.jpg');
            }
            session()->flash('success', 'دانشگاه با موفقیت ثبت شد');
        } else
            session()->flash('error', 'خطا در ثبت دانشگاه');
        return redirect()->back();
    }

    function editUniversity($id)
    {
        $university = University::find($id);
        $data = csrf_field() . '
<input name="editId" type="hidden" id="editId" value="' . $university->id . '">
<div class="row">
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editTitle" class="header-label">عنوان</label>
        <input name="editTitle" type="text" class="form-control" id="editTitle" value="' . $university->title . '">
    </div>
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editCity" class="header-label">شهر</label>
        <input name="editCity" type="text" class="form-control" id="editCity" value="' . $university->city . '">
    </div>
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editState" class="header-label">استان</label>
        <input name="editState" type="text" class="form-control" id="editState" value="' . $university->state . '">
    </div>
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editGeographicalLocation" class="header-label">مکان جغرافیایی</label>
        <input name="editGeographicalLocation" type="text" class="form-control" id="editGeographicalLocation" value="' . $university->geographical_location . '">
    </div>
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editCityCrowd" class="header-label">جمعیت شهر</label>
        <input name="editCityCrowd" type="text" class="form-control" id="editCityCrowd" value="' . $university->city_crowd . '">
    </div>
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editCostLiving" class="header-label">هزینه زندگی</label>
        <input name="editCostLiving" type="text" class="form-control" id="editCostLiving" value="' . $university->cost_living . '">
    </div>
    <div class="form-group float-label col-6">
        <label for="editImage" class="header-label">تصویر</label>
        <input name="editImage" type="file" class="form-control" id="editImage">
    </div>
    <div class="form-group float-label col-6">
        <label for="editLogo" class="header-label">لوگو</label>
        <input name="editLogo" type="file" class="form-control" id="editLogo">
    </div>
    <div class="col-6">
        <div class="form-group float-label">
            <label for="editLogoAcceptance" class="header-label">لوگوی پذیرفته شدگان</label>
            <input type="file" name="editLogoAcceptance" class="form-control form-control-sm" id="editLogoAcceptance">
        </div>
    </div>
    <div class="form-group col-12 col-lg-6 text-center">
        <br>
        <img src="' . route('imageUniversity', ['id' => $university->id, 'ua' => strtotime($university->updated_at)]) . '" width="80%">
    </div>
     <div class="form-group col-12 col-lg-6 text-center">
        <br>
        لوگو:
        <img src="' . route('logoUniversity', ['id' => $university->id, 'ua' => strtotime($university->updated_at)]) . '" width="80%">
    </div>
     <div class="form-group col-12 col-lg-6 text-center">
        <br>
        لوگوی پذیرفته شدگان:
        <img src="' . route('logoAcceptanceUniversity', ['id' => $university->id, 'ua' => strtotime($university->updated_at)]) . '" width="80%">
    </div>
</div>';
        return $data;
    }

    function updateUniversity(Request $request)
    {
        $rules = [
            'editTitle' => 'required|max:200|bad_chars',
            'editCity' => 'required|max:200|bad_chars',
            'editState' => 'required|max:200|bad_chars',
            'editGeographicalLocation' => 'required|max:200|bad_chars',
            'editCityCrowd' => 'required|should_be_nums',
            'editCostLiving' => 'nullable|should_be_nums'
        ];

        $customMessages = [
            'editTitle.required' => 'ورود عنوان الزامی است',
            'editTitle.max' => 'عنوان حداکثر باید 200 کاراکتر باشد',
            'editTitle.bad_chars' => 'عنوان حاوی کاراکتر های غیر مجاز است',

            'editCity.required' => 'ورود شهر الزامی است',
            'editCity.max' => 'شهر حداکثر باید 200 کاراکتر باشد',
            'editCity.bad_chars' => 'شهر حاوی کاراکتر های غیر مجاز است',

            'editState.required' => 'ورود استان الزامی است',
            'editState.max' => 'استان حداکثر باید 200 کاراکتر باشد',
            'editState.bad_chars' => 'استان حاوی کاراکتر های غیر مجاز است',

            'editGeographicalLocation.required' => 'ورود مکان جغرافیایی الزامی است',
            'editGeographicalLocation.max' => 'مکان جغرافیایی حداکثر باید 200 کاراکتر باشد',
            'editGeographicalLocation.bad_chars' => 'مکان جغرافیایی حاوی کاراکتر های غیر مجاز است',

            'editCityCrowd.required' => 'جمعیت شهر حاوی کاراکتر های غیر مجاز است',
            'editCityCrowd.should_be_nums' => 'جمعیت شهر فقط می تواند عدد باشد',

            'editCostLiving.should_be_nums' => 'هزینه زندگی فقط می تواند عدد باشد'
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()]);

        $university = University::find($request->editId);
        $university->title = $request->editTitle;
        $university->city = $request->editCity;
        $university->state = $request->editState;
        $university->geographical_location = $request->editGeographicalLocation;
        $university->city_crowd = $request->editCityCrowd;
        if ($request->editCostLiving)
            $university->cost_living = $request->editCostLiving;
        else
            $university->cost_living = 0;
        if ($university->save()) {
            if ($request->file('editImage')) {
                $folder = '/uploads/university/';
                $file = $request->file('editImage');
                $file->move(public_path() . $folder, $university->id . '.jpg');
            }
            if ($request->file('editLogo')) {
                $folder = '/uploads/university/';
                $file = $request->file('editLogo');
                $file->move(public_path() . $folder, $university->id . '_logo.jpg');
            }
            if ($request->file('editLogoAcceptance')) {
                $folder = '/uploads/university/';
                $file = $request->file('editLogoAcceptance');
                $file->move(public_path() . $folder, $university->id . '_acceptance_logo.jpg');
            }
            return 1;
        }
        return 2;
    }

    function deleteUniversity($id)
    {
        $university = University::find($id);
        if ($university->delete()) {

            UserUniversity::where('university_id', $id)->delere();

            if (is_file(public_path('uploads/university/' . $university->id . '.jpg')))
                unlink(public_path('uploads/university/' . $university->id . '.jpg'));
            if (is_file(public_path('uploads/university/' . $university->id . '_logo.jpg')))
                unlink(public_path('uploads/university/' . $university->id . '_logo.jpg'));
            session()->flash('success', 'دانشگاه حذف شد');
        } else
            session()->flash('error', 'خطا در حذف دانشگاه');
        return redirect()->back();
    }
}
