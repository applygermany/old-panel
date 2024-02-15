<?php

namespace App\Http\Controllers\Admin;

use App\Models\Comment;
use App\Models\ExpertTag;
use App\Models\TelSupport;
use App\Providers\MyHelpers;
use App\Models\User;
use App\Models\UserComment;
use App\Models\UserTelSupport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    public $permissions;

    function admins()
    {
        $admins = User::orderBy('id', 'DESC')->where('level', '<>', 1)->paginate(10);
        return view('admin.admins.admins', compact('admins'));
    }

    function getAdmins(Request $request)
    {
        $request->searchMobile = MyHelpers::numberToEnglish($request->searchMobile);
        $admin = User::Query();

        if ($request->searchFirstname)
            $admin->where('firstname', 'LIKE', '%' . $request->searchFirstname . '%');
        if ($request->searchLastname)
            $admin->where('lastname', 'LIKE', '%' . $request->searchLastname . '%');
        if ($request->searchMobile)
            $admin->where('mobile', 'LIKE', '%' . $request->searchMobile . '%');
        if ($request->searchEmail)
            $admin->where('email', 'LIKE', '%' . $request->searchEmail . '%');
        $admins = $admin->where('level', '<>', 1)->orderBy('id', 'DESC')->paginate(10);
        return view('admin.admins.list', compact('admins'))->render();
    }

    function validatePermission($key)
    {
        try {
            return $this->permissions->$key == 1 ? "checked" : "";
        } catch (Exception $e) {
            return "";
        }
    }

    function adminProfile($id)
    {
        $user = User::find($id);

        $activeTelSessions = TelSupport::where("user_id", "=", $id)->where(\DB::raw("UNIX_TIMESTAMP(day_tel)"), ">", time())->orderBy(\DB::raw("UNIX_TIMESTAMP(day_tel)"), "desc")->orderBy("from_time", "desc")->paginate(10);
        $pastTelSessions = UserTelSupport::where("supervisor_id", "=", $id)->where(\DB::raw("UNIX_TIMESTAMP(tel_date)"), "<=", time())->orderBy(\DB::raw("UNIX_TIMESTAMP(tel_date)"), "desc")->paginate(10);
        $comments = Comment::where("owner", "=", $id)->paginate(10);
        return view('admin.admins.profile', compact('user', 'activeTelSessions', 'pastTelSessions', 'comments'));
    }

    function saveAdmin(Request $request)
    {
        $rules = [
            'firstname' => 'required|max:200|bad_chars',
            'lastname' => 'required|max:200|bad_chars',
            'mobile' => 'required|max:11|min:10',
            'email' => 'nullable|max:250|email',
            'password' => 'required'
        ];

        $customMessages = [
            'firstname.required' => 'ورود نام الزامی است',
            'firstname.max' => 'نام حداکثر باید 200 کاراکتر باشد',
            'firstname.bad_chars' => 'نام حاوی کاراکتر های غیر مجاز است',

            'lastname.required' => 'ورود نام خانوادگی الزامی است',
            'lastname.max' => 'نام خانوادگی حداکثر باید 200 کاراکتر باشد',
            'lastname.bad_chars' => 'نام خانوادگی حاوی کاراکتر های غیر مجاز است',

            'mobile.required' => 'ورود موبایل الزامی است',
            'mobile.max' => 'موبایل حداکثر 11 رقم است',
            'mobile.min' => 'موبایل حداقل 10 رقم است',

            'email.email' => 'ایمیل معتبر نیست',
            'email.max' => 'موبایل حداکثر 250 رقم است',

            'password.required' => 'گذرواژه نمی تواند خالی باشد'
        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            session()->flash('error', 'خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (strlen($request->mobile) > 10)
            $request->mobile = MyHelpers::numberToEnglish($request->mobile);

        $admin = User::where('mobile', $request->mobile)->first();
        if ($admin) {
            session()->flash('error', 'این شماره موبایل قبلاً ثبت شده است');
            return redirect()->back()->withInput();
        }
        if ($request->email) {
            $admin = User::where('email', $request->email)->first();
            if ($admin) {
                session()->flash('error', 'این ایمیل قبلاً ثبت شده است');
                return redirect()->back()->withInput();
            }
        }
        $permissions = [];
        if ($request->level === 5) {
            foreach ($request->all() as $key => $value) {
                if (str_contains($key, "sup_perm_")) {
                    $permissions[str_replace("sup_perm_", "", $key)] = $value ? 1 : 0;
                }
            }
        } else {
            foreach ($request->all() as $key => $value) {
                if (str_contains($key, "perm_")) {
                    $permissions[str_replace("perm_", "", $key)] = $value ? 1 : 0;
                }
            }
        }

        $admin = new User();
        $admin->firstname = $request->firstname;
        $admin->lastname = $request->lastname;
        $admin->mobile = $request->mobile;
        $admin->admin_permissions = $permissions;
        if ($request->email)
            $admin->email = $request->email;
        $admin->password = bcrypt($request->password);
        $admin->level = $request->level;
        $admin->verified = 1;

        if (isset($request->sup_level_one) && isset($request->sup_level_two)) {
            $admin->sup_level = 'both';
        } elseif (isset($request->sup_level_one) && !isset($request->sup_level_two)) {
            $admin->sup_level = 'one';
        } elseif (!isset($request->sup_level_one) && isset($request->sup_level_two)) {
            $admin->sup_level = 'two';
        } else {
            $admin->sup_level = 'none';
        }

        if ($admin->save()) {
            if ($request->file('image')) {
                $folder = '/uploads/avatar/';
                $file = $request->file('image');
                $file->move(public_path() . $folder, $admin->id . '.jpg');
            }
            session()->flash('success', 'کاربر با موفقیت ثبت شد');
        } else
            session()->flash('error', 'خطا در ثبت کاربر');
        return redirect()->back();
    }

    function editAdmin($id)
    {
        $admin = User::find($id);
        $this->permissions = $admin->admin_permissions;

        $expert_tags = "";
        if ($admin->level == 2 || $admin->level == 3 || $admin->level === 5 || $admin->level === 7) {
            $tags = ExpertTag::where("expert_id", $id)->first();
            $t_ = explode("|", $tags->tags ?? "");
            $expert_tags = '<div class="form-group float-label col-6">
            <label for="description" class="header-label">توضیحات کارشناس</label>
            <textarea name="description" type="text" class="form-control" id="description" placeholder="این توضیحات در صفحه پشتیبان در پنل کاربر نمایش داده می شود">' . ($tags->description ?? "") . '</textarea>
            </div>
            <div class="col-6">
            <div class="form-group float-label col-12">
            <label for="t1" class="header-label">تگ 1</label>
            <input name="tag[]" type="text" class="form-control" id="t1" placeholder="تگ 1" value="' . ($t_[0] ?? "") . '">
            </div>
            <div class="form-group float-label col-12">
            <label for="t2" class="header-label">تگ 2</label>
            <input name="tag[]" type="text" class="form-control" id="t2" placeholder="تگ 2" value="' . ($t_[1] ?? "") . '">
            </div>
            <div class="form-group float-label col-12">
            <label for="t3" class="header-label">تگ 3</label>
            <input name="tag[]" type="text" class="form-control" id="t3" placeholder="تگ 3" value="' . ($t_[2] ?? "") . '">
            </div>
            </div>';
        }
        $data = csrf_field() . '
<input name="editId" type="hidden" id="editId" value="' . $admin->id . '">
<div class="row">
    <div class="form-group float-label col-6 col-lg-3">
        <label for="editEmail" class="header-label">ایمیل</label>
        <input name="editEmail" type="text" class="form-control" id="editEmail" value="' . $admin->email . '">
    </div>
    <div class="form-group float-label col-6 col-lg-3">
        <label for="editMobile" class="header-label">موبایل</label>
        <input name="editMobile" type="text" class="form-control" id="editMobile" value="' . $admin->mobile . '">
    </div>
    <div class="form-group float-label col-6 col-lg-3">
        <label for="editLevel" class="header-label">سطح دسترسی</label>
        <select name="editLevel" type="text" class="form-control" id="editLevel">
            <option value="">بدون تغییر</option>
            <option value="4">مدیر</option>
            <option value="3">کارشناس ارشد</option>
            <option value="5">کارشناس</option>
            <option value="2">پشتیبان</option>
            <option value="6">نگارنده</option>
            <option value="7">مشاور</option>
        </select>
    </div>
    <div class="form-group float-label col-6 col-lg-3">
        <label for="editImage" class="header-label">تصویر</label>
        <input name="editImage" type="file" class="form-control" id="editImage">
    </div>
    
    <div class="form-group float-label col-6">
        <label for="editFirstname" class="header-label">نام</label>
        <input name="editFirstname" type="text" class="form-control" id="editFirstname" value="' . $admin->firstname . '">
    </div>
    <div class="form-group float-label col-6">
        <label for="editLastname" class="header-label">نام خانوادگی</label>
        <input name="editLastname" type="text" class="form-control" id="editLastname" value="' . $admin->lastname . '">
    </div>
    <div class="row">
    <div class="p-2 col-4 ' . (($admin->level === 5 || $admin->level === 7 || $admin->level === 3) ? '' : 'd-none') . '">
    <label>
     لول 1
    <input type="checkbox" name="sup_level_one" id="sup_level_one" '.(($admin->sup_level === 'one' || $admin->sup_level === 'both') ? 'checked' : '').' />
    </label>
    </div>
    <div class="p-2 col-4 ' . (($admin->level === 5 || $admin->level === 7 || $admin->level === 3) ? '' : 'd-none') . '">
    <label>
     لول 2
    <input type="checkbox" name="sup_level_two" id="sup_level_two" '.(($admin->sup_level === 'two' || $admin->sup_level === 'both') ? 'checked' : '').'/>
    </label>
    </div>
    </div>
    <div class="p-2 col-12 ' . (($admin->level === 5 || $admin->level === 7 || $admin->level === 3) ? '' : 'd-none') . '">
    <label>
     مشاوره تلفنی ویژه
    <input type="checkbox" ' . ($id == auth()->user()->id ? "disabled" : "") . ' name="sup_perm_tel' . ($id == auth()->user()->id ? "2" : "") . '" ' . $this->validatePermission("sup_tel") . '>
    <input type="hidden"  name="sup_perm_tel' . ($id == auth()->user()->id ? "" : "2") . '" value="' . ($this->validatePermission("sup_tel") == "checked" ? 1 : 0) . '">
    </label>
    </div>
        <div class="p-2 col-12 ' . (($admin->level === 5 || $admin->level === 7 || $admin->level === 3) ? '' : 'd-none') . '">
    <label>
     مشاوره تلفنی عادی
    <input type="checkbox" ' . ($id == auth()->user()->id ? "disabled" : "") . ' name="sup_perm_tel_normal' . ($id == auth()->user()->id ? "2" : "") . '" ' . $this->validatePermission("sup_tel_normal") . '>
    <input type="hidden"  name="sup_perm_tel_normal' . ($id == auth()->user()->id ? "" : "2") . '" value="' . ($this->validatePermission("sup_tel_normal") == "checked" ? 1 : 0) . '">
    </label>
    </div>
    <div class="p-2 col-12 ' . ($admin->level !== 4 ? 'd-none' : '') . '">
    دسترسی ها
    <br>
    <label>
    داشبورد
<input type="checkbox" name="perm_dashboard" ' . $this->validatePermission("dashboard") . '>
</label>

    <label>
  
    مدیران
    <input type="checkbox" ' . ($id == auth()->user()->id ? "disabled" : "") . ' name="perm_admins' . ($id == auth()->user()->id ? "2" : "") . '" ' . $this->validatePermission("admins") . '>
    <input type="hidden"  name="perm_admins' . ($id == auth()->user()->id ? "" : "2") . '" value="' . ($this->validatePermission("admins") == "checked" ? 1 : 0) . '">
    </label>
    <label>
        کاربران
    <input type="checkbox" name="perm_users" ' . $this->validatePermission("users") . '>
    </label>
    
        <label>
        اطلاعات کاربران
    <input type="checkbox" name="perm_users_information" ' . $this->validatePermission("users_information") . '>
    </label>

    <label>
         سفارشات
    <input type="checkbox" name="perm_orders" ' . $this->validatePermission("orders") . '>
    </label>
    <label>
        اپلای 
    <input type="checkbox" name="perm_applies" ' . $this->validatePermission("applies") . '>
    </label>

    <label>
    وبینار 
    <input type="checkbox" name="perm_webinars" ' . $this->validatePermission("webinars") . '>
    </label>
    <label>
       دانشگاه ها
    <input type="checkbox" name="perm_universities" ' . $this->validatePermission("universities") . '>
    </label>
    <label>
    امور مالی
    <input type="checkbox" name="perm_financial" ' . $this->validatePermission("financial") . '>
    </label>
    <label>
    تنظیمات
    <input type="checkbox" name="perm_settings" ' . $this->validatePermission("settings") . '>
    </label>
    
    <label>
    چت
    <input type="checkbox" name="perm_chat" ' . $this->validatePermission("chat") . '>
    </label>
    <label>
    نوتیفیکیشن
    <input type="checkbox" name="perm_notification" ' . $this->validatePermission("notification") . '>
    </label>
    <label>
    ورژن پنل ها
    <input type="checkbox" name="perm_version" ' . $this->validatePermission("version") . '>
    </label>
    <label>
    گزارشات
    <input type="checkbox" name="perm_report" ' . $this->validatePermission("reports") . '>
    </label>
    <label>
    مدیر مالی
    <input type="checkbox" name="perm_financial_confirm" ' . $this->validatePermission("financial_confirm") . '>
    </label>
    <label>
    نتایج مشاوره
    <input type="checkbox" name="perm_telsupport_result" ' . $this->validatePermission("telsupport_result") . '>
    </label>
        <label>
    مشاوره تلفنی
    <input type="checkbox" name="perm_telSupports" ' . $this->validatePermission("telSupports") . '>
    </label>
<style>
    label{
        margin: 0 5px;
    }
</style>
  
</div>
    <div class="col-12 mb-3">
        <div class="alert alert-warning col-12">
                        در صورت خالی بودن گذرواژه ، رمز عبور بروزرسانی نمی شود
        </div>
    </div>
    <div class="form-group float-label col-6">
        <label for="editPassword" class="header-label">گذرواژه</label>
        <input name="editPassword" type="text" class="form-control" id="editPassword" placeholder="گذرواژه">
    </div>
    <div class="form-group float-label col-6">
        <label for="editPassword_confirmation" class="header-label">تایپ مجدد گذرواژه</label>
        <input name="editPassword_confirmation" type="text" class="form-control" id="editPassword_confirmation" placeholder="تایپ مجدد گذرواژه">
    </div>
    ' . $expert_tags . '
    <div class="form-group col-12 text-center">
        <br>
        <img src="' . route('imageUser', ['id' => $admin->id, 'ua' => strtotime($admin->updated_at)]) . '" width="5%">
    </div>
</div>';
        return $data;
    }

    function updateAdmin(Request $request)
    {
        $rules = [
            //'editEmail'=>'nullable|email|max:200',
            //'editMobile'=>'required|min:10|max:11',
            //'editFirstname'=>'required|max:200|bad_chars',
            //'editLastname'=>'required|max:200|bad_chars',
            'editPassword' => 'confirmed'
        ];

        $customMessages = [
            'editEmail.email' => 'ایمیل معتبر نیست',
            'editEmail.max' => 'ایمیل حداکثر 200 کاراکتر باید باشد',

            'editMobile.required' => 'موبایل را وارد کنید',
            'editMobile.max' => 'موبایل حداکثر 11 رقم است',
            'editMobile.min' => 'موبایل حداقل 10 رقم است',

            'editFirstname.required' => 'نام را وارد کنید',
            'editFirstname.max' => 'نام حداکثر باید 200 کاراکتر باشد',
            'editFirstname.bad_chars' => 'نام حاوی کاراکتر های غیر مجاز است',

            'editLastname.required' => 'نام خانوادگی را وارد کنید',
            'editLastname.max' => 'نام خانوادگی حداکثر باید 200 کاراکتر باشد',
            'editLastname.bad_chars' => 'نام خانوادگی حاوی کاراکتر های غیر مجاز است',

            'editPassword.confirmed' => 'گذرواژه با تایپ مجدد آن خوانایی ندارد'
        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()]);

        if ($request->editEmail) {
            $admin = User::where('email', $request->editEmail)->where('id', '!=', $request->editId)->first();
            if ($admin)
                return 3;
        }
        if ($request->editMobile) {
            if (strlen($request->editMobile) > 10)
                $request->editMobile = MyHelpers::numberToEnglish($request->editMobile);
            $admin = User::where('mobile', $request->editMobile)->where('id', '!=', $request->editId)->first();
            if ($admin)
                return 4;
        }
        $permissions = [];
        if ($request->level === 5 || $request->level === 7) {
            foreach ($request->all() as $key => $value) {
                if (str_contains($key, "sup_perm_")) {
                    $permissions[str_replace("sup_perm_", "", $key)] = $value ? 1 : 0;
                }
            }
        } else {
            foreach ($request->all() as $key => $value) {
                if (str_contains($key, "perm_")) {
                    $permissions[str_replace("perm_", "", $key)] = $value ? 1 : 0;
                }
            }
        }

        $admin = User::find($request->editId);
        if ($request->editLevel)
            $admin->level = $request->editLevel;
        if ($request->editEmail != null)
            $admin->email = $request->editEmail;
        else
            $admin->email = null;
        $admin->mobile = $request->editMobile;
        $admin->firstname = $request->editFirstname;
        $admin->lastname = $request->editLastname;
        $admin->admin_permissions = $permissions;
        if ($request->editPassword)
            $admin->password = bcrypt($request->editPassword);

        if (isset($request->sup_level_one) && isset($request->sup_level_two)) {
            $admin->sup_level = 'both';
        } elseif (isset($request->sup_level_one) && !isset($request->sup_level_two)) {
            $admin->sup_level = 'one';
        } elseif (!isset($request->sup_level_one) && isset($request->sup_level_two)) {
            $admin->sup_level = 'two';
        } else {
            $admin->sup_level = 'none';
        }

        if ($admin->save()) {

            if ($admin->level === 2 || $admin->level === 3 || $admin->level === 5 || $admin->level === 7) {
                $tag = ExpertTag::where("expert_id", $admin->id)->first();
                if (!$tag) {
                    $tag = new ExpertTag();
                }
                $tag->expert_id = $admin->id;
                $tag->description = $request->description;
                $tag->tags = join("|", $request->tag);
                $tag->save();
            }

            if ($request->file('editImage')) {
                $folder = '/uploads/avatar/';
                $file = $request->file('editImage');
                $file->move(public_path() . $folder, $admin->id . '.jpg');
            }
            return 1;
        }
        return 2;
    }

    function activateAdmin($id)
    {
        $admin = User::find($id);
        if ($admin->status == 2)
            $admin->status = 1;
        else
            $admin->status = 2;
        $admin->save();
        return $admin->status;
    }

    function getAdminComments(Request $request)
    {
        $comments = Comment::where("owner", "=", $request->id)->paginate(10);
        return view('admin.admins.commentList', compact('comments'))->render();
    }

    function deleteComment($id)
    {
        $comment = Comment::find($id);
        if ($comment->delete()) {
            session()->flash('success', 'حذف با موفقیت انجام گردید');
        } else {
            session()->flash('error', 'حذف با شکست مواجه گردید');
        }
        return redirect()->back();
    }

    function adminsTeam()
    {
        $admins = User::where('level', 5)->get();
        return view('admin.admins.team.index', compact('admins'));
    }

    function adminsTeamList($id)
    {
        $supports = User::where('expert_id', $id)->get();
        return view('admin.admins.team.partials.show', compact('supports'))->render();
    }

    function adminsTeamAdd($id)
    {
        $supports = User::where('level', 2)->where('expert_id', '<>', $id)->get();
        return view('admin.admins.team.partials.add', compact('supports', 'id'))->render();
    }

    function adminsTeamDelete($id)
    {
        $user = User::find($id);
        $user->expert_id = 0;
        if ($user->save()) {
            session()->flash('success', 'حذف پشتیبان با موفقیت انجام گردید');
        } else {
            session()->flash('error', 'حذف پشتیبان با شکست مواجه گردید');
        }
        return redirect()->back();
    }

    function adminsTeamAddSupport($expertId, $supportId)
    {
        $user = User::find($supportId);
        $user->expert_id = $expertId;
        if ($user->save()) {
            session()->flash('success', 'افزودن پشتیبان با موفقیت انجام گردید');
        } else {
            session()->flash('error', 'افزودن پشتیبان با شکست مواجه گردید');
        }
        return redirect()->back();
    }
}
