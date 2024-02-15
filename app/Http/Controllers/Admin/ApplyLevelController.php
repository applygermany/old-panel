<?php
namespace App\Http\Controllers\Admin;

use App\Models\ApplyLevel;
use App\Models\ApplyLevelTitle;
use App\Providers\MyHelpers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ApplyLevelController extends Controller
{
    public function applyLevels()
    {
        $applyLevels = ApplyLevel::orderBy('pos', 'DESC')->paginate(10);
        return view('admin.applyLevels.applyLevels', compact('applyLevels'));
    }

    public function getApplyLevels(Request $request)
    {
        $applyLevel = ApplyLevel::Query();
        if ($request->searchTitle) {
            $applyLevel->where('title', 'LIKE', '%' . $request->searchTitle . '%');
        }

        if ($request->searchPos) {
            $applyLevel->where('pos', 'LIKE', '%' . $request->searchPos . '%');
        }

        $applyLevels = $applyLevel->orderBy('pos', 'DESC')->paginate(10);
        return view('admin.applyLevels.list', compact('applyLevels'))->render();
    }

    public function saveApplyLevel(Request $request)
    {
        $rules = [
            'title' => 'required|max:200',
            'pos' => 'required|should_be_nums',
            'text' => 'required',
            'phase' => 'required|should_be_nums',
            'phasePercent' => 'required|should_be_nums|should_be_pos|max:100',
            'progressPercent' => 'required|should_be_nums|should_be_pos|max:100',
          
        ];

        $customMessages = [
            'title.required' => 'ورود عنوان الزامی است',
            'title.max' => 'عنوان حداکثر باید 200 کاراکتر باشد',

            'pos.required' => 'ورود جایگاه الزامی است',
            'pos.should_be_nums' => 'جایگاه فقط می تواند عدد باشد',

            'text.required' => 'وارد کردن متن الزامی است',

            'phase.required' => 'وارد کردن فاز الزامی است',
            'phase.should_be_nums' => 'فاز باید عدد باشد',

            'phasePercent.required' => 'وارد کردن درصد فاز الزامی است',
            'phasePercent.should_be_nums' => 'درصد فاز باید عدد باشد',
            'phasePercent.should_be_pos' => 'درصد فاز باید بزرگتر از 0 باشد',
            'phasePercent.max' => 'درصد فاز باید کوچکتر از 100 باشد',

            'progressPercent.required' => 'وارد کردن درصد پیشرفت الزامی است',
            'progressPercent.should_be_nums' => 'درصد پیشرفت باید عدد باشد',
            'progressPercent.should_be_pos' => 'درصد پیشرفت باید بزرگتر از 0 باشد',
            'progressPercent.max' => 'درصد پیشرفت باید کوچکتر از 100 باشد',

            'filename.max' => 'اسم فایل حداکثر باید 200 کاراکتر باشد',
            'filename.bad_chars' => 'اسم فایل حاوی کاراکتر های غیر مجاز است',
        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            session()->flash('error', 'خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $applyLevel = new ApplyLevel();
        $applyLevel->title = $request->title;
        $applyLevel->pos = $request->pos;
        $applyLevel->text = $request->text;
        $applyLevel->link = $request->link;
        $applyLevel->phase = $request->phase;
        $applyLevel->phase_percent = $request->phasePercent;
        $applyLevel->progress_percent = $request->progressPercent;
        if ($request->nextLevelButton) {
            $applyLevel->next_level_button = $request->nextLevelButton;
        } else {
            $applyLevel->next_level_button = "این مطلب را خواندم و از آن مطلع شدم";
        }

    

        if ($applyLevel->save()) {
            ApplyLevelTitle::where("apply_level_id", $applyLevel->id)->orderBy("id", "desc")->delete();
            $files = glob(public_path('/uploads/level-file/'.$applyLevel->id."/*")); 
            foreach ($files as $file) { 
                if (is_file($file)) {
                    unlink($file); 
                }
            }

            foreach ($request->filename as $filename) {
                $nA = new ApplyLevelTitle();
                $nA->title = $filename;
                $nA->apply_level_id = $applyLevel->id;
                $nA->save();
            }

            if ($request->file('image')) {
                $folder = '/uploads/level/';
                $file = $request->file('image');
                $file->move(public_path() . $folder, $applyLevel->id );
            }

            $files = $request->file('files');

            if ($request->hasFile('files')) {
                foreach ($files as $key => $file) {
                    $folder = '/uploads/level-file/' . $applyLevel->id . "/";
                    $file->move(public_path() . $folder, $key . '.' . $file->getClientOriginalExtension());
                }
            }
            if ($request->file('image')) {
                $folder = '/uploads/level/';
                $file = $request->file('image');
                $file->move(public_path() . $folder, $applyLevel->id . '.jpg');
            }
            if ($request->file('file')) {
                $folder = '/uploads/level-file/';
                $file = $request->file('file');
                $file->move(public_path() . $folder, $applyLevel->id . '.' . $file->getClientOriginalExtension());
            }
            session()->flash('success', 'مرحله اپلای با موفقیت ثبت شد');
        } else {
            session()->flash('error', 'خطا در ثبت مرحله اپلای');
        }

        return redirect()->back();
    }

    public function editApplyLevel($id)
    {
        $applyLevel = ApplyLevel::find($id);
        return view('admin.applyLevels.edit', compact('applyLevel'));
    }

    public function updateApplyLevel(Request $request)
    {

        $rules = [
            'title' => 'required|max:200',
            'pos' => 'required|should_be_nums',
            'text' => 'required',
            'phase' => 'required|should_be_nums',
            'phasePercent' => 'required|should_be_nums|should_be_pos|max:100',
            'progressPercent' => 'required|should_be_nums|should_be_pos|max:100',
            'nextLevelButton' => 'max:200|bad_chars',
        ];

        $customMessages = [
            'title.required' => 'ورود عنوان الزامی است',
            'title.max' => 'عنوان حداکثر باید 200 کاراکتر باشد',

            'pos.required' => 'ورود جایگاه الزامی است',
            'pos.should_be_nums' => 'جایگاه فقط می تواند عدد باشد',

            'text.required' => 'وارد کردن متن الزامی است',

            'phase.required' => 'وارد کردن فاز الزامی است',
            'phase.should_be_nums' => 'فاز باید عدد باشد',

            'phasePercent.required' => 'وارد کردن درصد فاز الزامی است',
            'phasePercent.should_be_nums' => 'درصد فاز باید عدد باشد',
            'phasePercent.should_be_pos' => 'درصد فاز باید بزرگتر از 0 باشد',
            'phasePercent.max' => 'درصد فاز باید کوچکتر از 100 باشد',

            'progressPercent.required' => 'وارد کردن درصد پیشرفت الزامی است',
            'progressPercent.should_be_nums' => 'درصد پیشرفت باید عدد باشد',
            'progressPercent.should_be_pos' => 'درصد پیشرفت باید بزرگتر از 0 باشد',
            'progressPercent.max' => 'درصد پیشرفت باید کوچکتر از 100 باشد',

            'nextLevelButton.required' => 'ورود متن دکمه الزامی است',
            'nextLevelButton.max' => 'متن دکمه حداکثر باید 200 کاراکتر باشد',
            'nextLevelButton.bad_chars' => 'متن دکمه حاوی کاراکتر های غیر مجاز است',
        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            session()->flash('error', 'خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $applyLevel = ApplyLevel::find($request->id);
        $applyLevel->title = $request->title;
        $applyLevel->pos = $request->pos;
        $applyLevel->text = $request->text;
        if ($request->link) {
            $applyLevel->link = $request->link;
        } else {
            $applyLevel->link = null;
        }

        $applyLevel->phase = $request->phase;
        $applyLevel->phase_percent = MyHelpers::numberToEnglish($request->phasePercent);
        $applyLevel->progress_percent = MyHelpers::numberToEnglish($request->progressPercent);
        if ($request->nextLevelButton) {
            $applyLevel->next_level_button = $request->nextLevelButton;
        } else {
            $applyLevel->next_level_button = "این مطلب را خواندم و از آن مطلع شدم";
        }

        if ($applyLevel->save()) {
            ApplyLevelTitle::where("apply_level_id", $applyLevel->id)->orderBy("id", "desc")->delete();
            $files = glob(public_path('/uploads/level-file/'.$applyLevel->id."/*")); 
            foreach ($files as $file) { 
                if (is_file($file)) {
//                    unlink($file);
                }
            }

            foreach ($request->filename as $filename) {
                $nA = new ApplyLevelTitle();
                $nA->title = $filename;
                $nA->apply_level_id = $applyLevel->id;
                $nA->save();
            }

            if ($request->file('image')) {
                $folder = '/uploads/level/';
                $file = $request->file('image');
                $file->move(public_path() . $folder, $applyLevel->id . '.jpg');
            }

            $files = $request->file('files');

            if ($request->hasFile('files')) {
                foreach ($files as $key => $file) {
                    $folder = '/uploads/level-file/' . $applyLevel->id . "/";
                    $file->move(public_path() . $folder, $key . '.' . $file->getClientOriginalExtension());
                }
            }

            session()->flash('success', 'مرحله اپلای با موفقیت ویرایش شد');
        } else {
            session()->flash('error', 'خطا در ویرایش مرحله اپلای');
        }

        return redirect()->back();
    }

    public function deleteApplyLevel($id)
    {
        $applyLevel = ApplyLevel::find($id);
        if ($applyLevel->delete()) {
            if (is_file(public_path('uploads/level/' . $applyLevel->id . '.jpg'))) {
                unlink(public_path('uploads/level/' . $applyLevel->id . '.jpg'));
            }

            $files = glob(public_path('uploads/level-file/' . $id . '.*'));
            if (count($files) > 0) {
                if (is_file($files[0])) {
                    unlink($files[0]);
                }

            }
            session()->flash('success', 'مرحله اپلای با موفقیت حذف شد');
        } else {
            session()->flash('error', 'خطا در حذف مرحله اپلای');

        }
        return redirect()->back();
    }
}
