<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Models\UserExtraUniversity;
use App\Providers\HesabFa;
use App\ExcelExports\Users;
use App\Http\Services\V1\User\TelSupportService;
use App\Mail\MailVerificationCode;
use App\Models\Acceptance;
use App\Models\TelSupport;
use App\Models\Transaction;
use App\Models\University;
use App\Models\UserSupervisor;
use App\Models\UserTelSupport;
use App\Models\UserUniversity;
use App\Providers\MyHelpers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Providers\JDF;
use App\Providers\Notification;
use App\Providers\SMS;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    function users()
    {
        $users = User::orderBy('created_at', 'DESC')->where("status", 1)->where("verified", 1)->where('level', 1)->paginate(10);
        $categories = Category::all();
        return view('admin.users.users', compact('users', 'categories'));
    }

    function userExcelExports(){
        $users = User::orderBy('created_at', 'DESC')->where("status", 1)->where("verified", 1)->where('level', 1)->paginate(10);
        $categories = Category::all();
        return view('admin.users.export', compact('users', 'categories'));
    }
    public function exportUsersExcelOld(Request $request)
    {
        $query = User::Query();
        $query->where([['mobile', '<>', null], ['mobile', '!=', '']]);
        $query->where('level','=',1);
        if($request->searchType)
            $query->where('type', $request->searchType);
        if($request->telSupportStatus==1){
            $query->doesntHave('userTelSupports');
        }else if($request->telSupportStatus==2){
            $query->has('userTelSupports');
        }
        $users= $query->with('acceptances')->get();

        $fromDateArray=explode('/',$request->fromdate);
        foreach ($fromDateArray as $key=>$value){
            $fromDateArray[$key]=MyHelpers::numberToEnglish($value);
        }

        $toDateArray=explode('/',$request->todate);
        foreach ($toDateArray as $key=>$value){
            $toDateArray[$key]=MyHelpers::numberToEnglish($value);
        }
//        $users = User::where([['level', 1], ['type', 1], ['mobile', '<>', null], ['mobile', '!=', ''], ['type', $request->searchType]])->select('id', 'firstname', 'lastname', 'mobile', 'created_at')->doesntHave('telSupports')->get();
        $filterUsers = [];
        foreach ($users as $key => $value) {
            $jalaliDate = MyHelpers::dateToJalali($users[$key]['created_at']);
            $arrayDate = explode('/', $jalaliDate);
            $users[$key]['jalaliDate'] = $jalaliDate;


            if ($arrayDate[0] >= $fromDateArray[0] and $arrayDate[0] <= $toDateArray[0]) {
                if ($arrayDate[1] >= $fromDateArray[1] and $arrayDate[1] <= $toDateArray[1]) {
                    if ($arrayDate[2] >= $fromDateArray[2] and $arrayDate[2] <= $toDateArray[2]) {
                        array_push($filterUsers, $users[$key]);
                    }
                }
            }
        }
        $fake = ["searchFirstname" => null, "searchLastname" => null,
            "fromdate" => null,
            "todate" => null,
            "searchType" => null,
            "searchPhone" => null,
            "users" => $filterUsers
        ];

        $request = new Request($fake);
        return Excel::download(new Users($request), 'users.xlsx');
    }
    public function exportUsersExcel(Request $request)
    {
        $query = User::Query();
        $fromDateArray=explode('/',$request->fromdate);
        $fromDate= JDF::jalali_to_gregorian($fromDateArray[0],$fromDateArray[1],$fromDateArray[2],'-').' 00:00:00';
        $toDateArray=explode('/',$request->todate);
        $toDate= JDF::jalali_to_gregorian($toDateArray[0],$toDateArray[1],$toDateArray[2],'-').' 00:00:00';
        $query->where([['mobile', '<>', null], ['mobile', '!=', '']])->whereDate('created_at','>=',$fromDate)->whereDate('created_at','<=',$toDate);
        $query->where('level','=',1);
        if($request->searchType)
            $query->where('type', $request->searchType);
        if($request->telSupportStatus==1){
            $query->doesntHave('userTelSupports');
        }else if($request->telSupportStatus==2){
            $query->has('userTelSupports');
        }
        $users= $query->with('acceptances')->get();


        $fake = ["searchFirstname" => null, "searchLastname" => null,
            "fromdate" => null,
            "todate" => null,
            "searchType" => null,
            "searchPhone" => null,
            "users" => $users
        ];

        $request = new Request($fake);
        return Excel::download(new Users($request), 'users.xlsx');
    }

    function usersInformation()
    {
        $users = User::orderBy('created_at', 'DESC')->where("status", 1)->where('type', [2, 3])->where("verified", 1)->where('level', 1)->paginate(10);
        $categories = Category::all();
        return view('admin.users.information.users', compact('users', 'categories'));
    }

    function getUsers(Request $request)
    {

        $request->searchMobile = MyHelpers::numberToEnglish($request->searchMobile);
        $user = User::Query();
        if ($request->searchType)
            $user->where('type', $request->searchType);
        if ($request->searchFirstname)
            $user->where('firstname', 'LIKE', '%' . $request->searchFirstname . '%');
        if ($request->searchLastname)
            $user->where('lastname', 'LIKE', '%' . $request->searchLastname . '%');
        if ($request->searchMobile)
            $user->where('mobile', 'LIKE', '%' . $request->searchMobile . '%');
        if ($request->searchEmail)
            $user->where('email', 'LIKE', '%' . $request->searchEmail . '%');
        if ($request->fromdate != "") {
            $request->fromdate = JDF::jalali_to_gregorian_(MyHelpers::numberToEnglish($request->fromdate), "/");
            $user->where('created_at', '>', $request->fromdate);
        }
        if ($request->todate != "") {
            echo $request->todate = JDF::jalali_to_gregorian_(MyHelpers::numberToEnglish($request->todate), "/");
            $user->where('created_at', '<', $request->todate);
        }
        $users = $user->where('level', 1)->orderBy('created_at', 'DESC')->paginate(10);
        $categories = Category::all();
        return view('admin.users.list', compact('users', 'categories'))->render();
    }

    function getUsersInformation(Request $request)
    {
        $request->searchMobile = MyHelpers::numberToEnglish($request->searchMobile);
        $user = User::Query();
        if ($request->searchType)
            $user->where('type', $request->searchType);
        if ($request->searchFirstname)
            $user->where('firstname', 'LIKE', '%' . $request->searchFirstname . '%');
        if ($request->searchLastname)
            $user->where('lastname', 'LIKE', '%' . $request->searchLastname . '%');
        if ($request->searchMobile)
            $user->where('mobile', 'LIKE', '%' . $request->searchMobile . '%');
        if ($request->searchEmail)
            $user->where('email', 'LIKE', '%' . $request->searchEmail . '%');
        if ($request->contractCode)
            $user->where('contract_code', 'LIKE', '%' . $request->contractCode . '%');
        if ($request->nationalCode)
            $user->where('codemelli', 'LIKE', '%' . $request->nationalCode . '%');
        if ($request->fromdate != "") {
            $request->fromdate = JDF::jalali_to_gregorian_(MyHelpers::numberToEnglish($request->fromdate), "/");
            $user->where('created_at', '>', $request->fromdate);
        }
        if ($request->todate != "") {
            echo $request->todate = JDF::jalali_to_gregorian_(MyHelpers::numberToEnglish($request->todate), "/");
            $user->where('created_at', '<', $request->todate);
        }
        $users = $user->where('level', 1)->where('type', [2, 3])->orderBy('created_at', 'DESC')->paginate(10);
        $categories = Category::all();
        return view('admin.users.information.list', compact('users', 'categories'))->render();
    }

    function saveUser(Request $request)
    {
        $rules = [
            'mobile' => 'required|max:11|min:10',
            'email' => 'nullable|max:250|email',
            'password' => 'required',
            'firstname' => 'required|max:200|bad_chars',
            'lastname' => 'required|max:200|bad_chars',
        ];
        $customMessages = [
            'mobile.required' => 'ورود موبایل الزامی است',
            'mobile.max' => 'موبایل حداکثر 11 رقم است',
            'mobile.min' => 'موبایل حداقل 10 رقم است',
            'email.email' => 'ایمیل معتبر نیست',
            'email.max' => 'موبایل حداکثر 250 رقم است',
            'password.required' => 'گذرواژه نمی تواند خالی باشد',
            'firstname.required' => 'ورود نام الزامی است',
            'firstname.max' => 'نام حداکثر باید 200 کاراکتر باشد',
            'firstname.bad_chars' => 'نام حاوی کاراکتر های غیر مجاز است',
            'lastname.required' => 'ورود نام خانوادگی الزامی است',
            'lastname.max' => 'نام خانوادگی حداکثر باید 200 کاراکتر باشد',
            'lastname.bad_chars' => 'نام خانوادگی حاوی کاراکتر های غیر مجاز است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            session()->flash('error', 'خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        if (strlen($request->mobile) > 10)
            $request->mobile = MyHelpers::numberToEnglish(substr($request->mobile, 1, 10));
        $user = User::where('mobile', $request->mobile)->first();
        if ($user) {
            session()->flash('error', 'این شماره موبایل قبلاً ثبت شده است');
            return redirect()->back()->withInput();
        }
        if ($request->email) {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                session()->flash('error', 'این ایمیل قبلاً ثبت شده است');
                return redirect()->back()->withInput();
            }
        }
        $user = new User();
        $user->mobile = $request->mobile;
        if ($request->email)
            $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->password = bcrypt($request->password);
        $user->level = 1;
        $user->verified = 1;
        if ($user->save()) {
            $acc = new HesabFa;
            $acc->addCustomer($user);
            if ($request->file('image')) {
                $folder = '/uploads/avatar/';
                $file = $request->file('image');
                $file->move(public_path() . $folder, $user->id . '.jpg');
            }
            session()->flash('success', 'کاربر با موفقیت ثبت شد');
        } else
            session()->flash('error', 'خطا در ثبت کاربر');
        return redirect()->back();
    }

    function editUser($id)
    {
        $user = User::find($id);
        $supervisorOptions = '<option value="">بدون تغییر</option>';
        $supportOptions = '<option value="">بدون تغییر</option>';
        $supervisors = User::where('level', 5)->get();
        $supports = User::whereIn('level', [2, 5])->get();
        $userSupervisors = UserSupervisor::where('user_id', $user->id)->get();

        $supervisorName = '';
        foreach ($supervisors as $supervisor) {
            if ($userSupervisors->where('supervisor_id', $supervisor->id)->first()) {
                $supervisorName = $supervisor->firstname . ' ' . $supervisor->lastname;
                break;
            }
        }
        foreach ($supports as $support) {
            $selected = '';
            if ($userSupervisors->where('supervisor_id', $support->id)->first())
                $selected = 'selected';
            $supportOptions .= '<option data-expert-id="' . $support->expert()->id . '" data-supervisorName="' . $support->expert()->firstname . ' ' . $support->expert()->lastname . '" value="' . $support->id . '" ' . $selected . '>' . $support->firstname . ' ' . $support->lastname . '</option>';
        }
        $data = csrf_field() . '
<input name="editId" type="hidden" id="editId" value="' . $user->id . '">
<input name="editSupervisor" type="hidden" id="editSupervisor" value="0">
<div class="row">
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editEmail" class="header-label">ایمیل</label>
        <input name="editEmail" type="text" class="form-control" id="editEmail" value="' . $user->email . '">
    </div>
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editMobile" class="header-label">موبایل</label>
        <input name="editMobile" type="text" class="form-control" id="editMobile" value="' . $user->mobile . '">
    </div>
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editType" class="header-label">ویژه ؟</label>
            <select ' . ($user->type == 1 ? 'disabled' : '') . ' name="editType" type="text" class="form-control" id="editType">
            <option value="">بدون تغییر</option>
            <option value="2">پکیج طلایی</option>
            <option value="3">پکیج نقره ای</option>
            <option value="1">خیر</option>
        </select>
    </div>

     <div class="form-group float-label col-6">
        <label for="editSupervisorName" class="header-label">کارشناس</label>
         <input disabled type="text" class="form-control" id="editSupervisorName" value="' . $supervisorName . '">
    </div>
    <div class="form-group float-label col-6">
        <label for="editSupport" class="header-label">پشتیبان</label>
        <select name="editSupport" type="text" class="form-control editSupport" id="editSupport">
            ' . $supportOptions . '
        </select>
    </div>
    
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editFirstname" class="header-label">نام</label>
        <input name="editFirstname" type="text" class="form-control" id="editFirstname" value="' . $user->firstname . '">
    </div>
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editLastname" class="header-label">نام خانوادگی</label>
        <input name="editLastname" type="text" class="form-control" id="editLastname" value="' . $user->lastname . '">
    </div>
    <div class="form-group float-label col-12 col-lg-4">
        <label for="editImage" class="header-label">تصویر</label>
        <input name="editImage" type="file" class="form-control" id="editImage">
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
   
    <div class="form-group col-12 text-center">
        <br>
        <img src="' . route('imageUser', ['id' => $user->id, 'ua' => strtotime($user->updated_at)]) . '" width="30%">
    </div>
</div>';
        return $data;
    }

    function updateUser(Request $request)
    {
        $rules = [
            //'editEmail'=>'nullable|email|max:200',
            //'editMobile'=>'required|min:10|max:11',
            // 'editFirstname'=>'required|max:200|bad_chars',
            //'editLastname'=>'required|max:200|bad_chars',
            'editPassword' => 'confirmed',
        ];
        $customMessages = [
            'editEmail.email' => 'ایمیل معتبر نیست',
            'editEmail.max' => 'ایمیل حداکثر 200 کاراکتر باید باشد',
            'editMobile.required' => 'موبایل را وارد کنید',
            'editMobile.max' => 'موبایل حداکثر 11 رقم است',
            'editMobile.min' => 'موبایل حداقل 10 رقم است',
            'editFirstname.required' => 'ورود نام الزامی است',
            'editFirstname.max' => 'نام حداکثر باید 200 کاراکتر باشد',
            'editFirstname.bad_chars' => 'نام حاوی کاراکتر های غیر مجاز است',
            'editLastname.required' => 'ورود نام خانوادگی الزامی است',
            'editLastname.max' => 'نام خانوادگی حداکثر باید 200 کاراکتر باشد',
            'editLastname.bad_chars' => 'نام خانوادگی حاوی کاراکتر های غیر مجاز است',
            'editPassword.confirmed' => 'گذرواژه با تایپ مجدد آن خوانایی ندارد',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()]);

        $user = User::find($request->editId);

        if ($request->editType) {
            if ($request->editType == 1) {
                $user->acceptances()->delete();
                $user->telSupports()->delete();
                $user->uploads()->delete();
                $user->userTelSupports()->delete();
                //	$user->universities()->delete();
                UserSupervisor::where("user_id", $user->id)->delete();
                UserTelSupport::where(DB::raw("UNIX_TIMESTAMP(tel_date) "), ">", time())->where('user_id', $user->id)->delete();
            }
            $user->contract_code = null;
            $user->type = $request->editType;
        }

        if ($request->editEmail != NULL)
            $user->email = $request->editEmail;
        else
            $user->email = NULL;
        $user->mobile = $request->editMobile;
        $user->firstname = $request->editFirstname;
        $user->lastname = $request->editLastname;
        if ($request->editPassword)
            $user->password = bcrypt($request->editPassword);
        if ($user->save()) {
            $userSupervisor = UserSupervisor::where('user_id', $user->id)->get();
            $support_set = 0;
            $supervisor_set = 0;

            foreach ($userSupervisor as $sup) {
                if ($sup->supervisor->level == 2) {
                    $old = $sup->supervisor;
                    $new = User::find($request->editSupport);
                    $support_set = 1;
                    if ($old->id !== $new->id) {

                        //new date
                        $user->support_assign_date = Carbon::now();
                        $user->save();

                        //Delete old
                        $sup->delete();

                        //insert new
                        $support = new UserSupervisor();
                        $support->user_id = $user->id;
                        $support->supervisor_id = $request->editSupport;
                        $support->save();

                        $send = (new SMS())->sendVerification($user->mobile, "change_support", "name==" . $user->firstname . " " . $user->lastname . "&name_1==" . $old->firstname . " " . $old->lastname . "&name_2==" . $new->firstname . " " . $new->lastname);
                        $mail = User::sendMail(new MailVerificationCode("change_support", [
                            $user->firstname . " " . $user->lastname,
                            $old->firstname . " " . $old->lastname,
                            $new->firstname . " " . $new->lastname,
                        ], "change_support"), $user->email);
                        $notif = (new Notification("change_support", [
                            $user->firstname . " " . $user->lastname,
                            $old->firstname . " " . $old->lastname,
                            $new->firstname . " " . $new->lastname,
                        ]))->send($user->id);

                        //to expert
                        $notif = (new Notification("expert_user_added", [
                            $new->firstname,
                            $user->firstname . " " . $user->lastname,
                        ]))->send($new->id);
                        $mail = User::sendMail(new MailVerificationCode("expert_user_added", [
                            $new->firstname,
                            $user->firstname . " " . $user->lastname,
                        ], "expert_user_added"), $new->email);
                    }
                } else if ($sup->supervisor->level == 5) {
                    $old = $sup->supervisor;
                    $new = User::find($request->editSupervisor);
                    $supervisor_set = 1;
                    if ($old->id !== $new->id) {

                        //new date
                        $user->expert_assign_date = Carbon::now();
                        $user->save();

                        //delete old
                        $sup->delete();

                        $support = new UserSupervisor();
                        $support->user_id = $user->id;
                        $support->supervisor_id = $request->editSupervisor;
                        $support->save();

                        $send = (new SMS())->sendVerification($user->mobile, "change_supervisor", "name==" . $user->firstname . " " . $user->lastname . "&name_1==" . $old->firstname . " " . $old->lastname . "&name_2==" . $new->firstname . " " . $new->lastname);
                        $mail = User::sendMail(new MailVerificationCode("change_supervisor", [
                            $user->firstname . " " . $user->lastname,
                            $old->firstname . " " . $old->lastname,
                            $new->firstname . " " . $new->lastname,
                        ], "change_support"), $user->email);
                        $notif = (new Notification("change_supervisor", [
                            $user->firstname . " " . $user->lastname,
                            $old->firstname . " " . $old->lastname,
                            $new->firstname . " " . $new->lastname,
                        ]))->send($user->id);

                        //to expert
                        $notif = (new Notification("expert_user_added", [
                            $new->firstname,
                            $user->firstname . " " . $user->lastname,
                        ]))->send($new->id);
                    }
                }
            }

            if ($support_set == 0) {
                if ($request->editSupport) {

                    $user->support_assign_date = Carbon::now();
                    $user->save();

                    $support = new UserSupervisor();
                    $support->user_id = $user->id;
                    $support->supervisor_id = $request->editSupport;
                    $support->save();
                    $expert = $support->supervisor;
                    $send = (new SMS())->sendVerification($user->mobile, "add_support", "name==" . $user->firstname . " " . $user->lastname . "&name_1==" . $expert->firstname . " " . $expert->lastname);
                    $mail = User::sendMail(new MailVerificationCode("add_support", [
                        $user->firstname . " " . $user->lastname,
                        $expert->firstname . " " . $expert->lastname,
                    ], "add_support"), $user->email);
                    $notif = (new Notification("add_support", [
                        $user->firstname . " " . $user->lastname,
                        $expert->firstname . " " . $expert->lastname,
                    ]))->send($user->id);

                    //to expert
                    $notif = (new Notification("expert_user_added", [
                        $expert->firstname,
                        $user->firstname . " " . $user->lastname,
                    ]))->send($expert->id);
                    $mail = User::sendMail(new MailVerificationCode("expert_user_added", [
                        $expert->firstname,
                        $user->firstname . " " . $user->lastname,
                    ], "expert_user_added"), $expert->email);
                }
            }

            if ($supervisor_set == 0) {
                if ($request->editSupervisor) {
                    $support = new UserSupervisor();
                    $support->user_id = $user->id;
                    $support->supervisor_id = $request->editSupervisor;
                    $support->save();

                    $user->support_assign_date = Carbon::now();
                    $user->save();

                    $expert = $support->supervisor;
                    $send = (new SMS())->sendVerification($user->mobile, "add_supervisor", "name==" . $user->firstname . " " . $user->lastname . "&name_1==" . $expert->firstname . " " . $expert->lastname);

                    $mail = User::sendMail(new MailVerificationCode("add_supervisor", [
                        $user->firstname . " " . $user->lastname,
                        $expert->firstname . " " . $expert->lastname,
                    ], "add_supervisor"), $user->email);
                    $notif = (new Notification("add_supervisor", [
                        $user->firstname . " " . $user->lastname,
                        $expert->firstname . " " . $expert->lastname,
                    ]))->send($user->id);
                    //to expert

                    $notif = (new Notification("expert_user_added", [
                        $expert->firstname,
                        $user->firstname . " " . $user->lastname,
                    ]))->send($expert->id);
                }
            }

            if ($request->file('editImage')) {
                $folder = '/uploads/avatar/';
                $file = $request->file('editImage');
                $file->move(public_path() . $folder, $user->id . '.jpg');
            }
            return 1;
        }
        return 2;
    }

    function activateUser($id)
    {
        $user = User::find($id);
        if ($user->status == 2) {
            $user->status = 1;
        } else {
            $user->status = 2;
            UserTelSupport::where("user_id", $id)->delete();
        }
        $user->save();
        return $user->status;
    }

    function deleteUser($id)
    {
        $user = User::find($id);
        if ($user->delete())
            session()->flash('success', 'کاربر با موفقیت حذف شد');
        else
            session()->flash('error', 'خطا در حذف کاربر');
        return redirect()->back();
    }

    function userProfile($id)
    {
        $user = User::find($id);
        $acceptances = $user->acceptances()->orderBy('id', 'DESC')->paginate(10);
        $universities = $user->universities()->orderBy('id', 'DESC')->paginate(10);
        $invites = $user->invites()->orderBy('id', 'DESC')->paginate(10);
        $transactions = $user->invoices()->orderBy('id', 'DESC')->paginate(10);
        $resumes = $user->resume()->where("status", ">", 0)->orderBy('id', 'DESC')->paginate(10);
        $motivations = $user->motivations()->where("status", ">", 0)->orderBy('id', 'DESC')->paginate(10);
        $userTelSupports = $user->userTelSupports()->orderBy('id', 'DESC')->paginate(10);
        $uploads = $user->uploads()->orderBy('id', 'DESC')->paginate(10);
        $allUniversities = University::select('id', 'title')->get();
        return view('admin.users.profile', compact('user', 'acceptances', 'universities', 'transactions', 'userTelSupports', 'uploads', 'allUniversities', 'resumes', 'motivations', 'invites'));
    }

    function userProfileInformation($id)
    {
        $user = User::find($id);
        $universities = $user->universities()->orderBy('id', 'DESC')->get();
        $transactions = $user->invoices()->orderBy('id', 'DESC')->get();
        $resumes = $user->resume()->where("status", ">", 0)->orderBy('id', 'DESC')->get();
        $motivations = $user->motivations()->where("status", ">", 0)->orderBy('id', 'DESC')->get();
        return view('admin.users.information.profile', compact('user', 'universities', 'transactions', 'resumes', 'motivations'));
    }

    function addUserUniversity(Request $request)
    {
        $rules = [
            'university' => 'required',
            'field' => 'nullable|bad_chars',
            'link' => 'nullable|url',
            'chanceGetting' => 'nullable|min:0|max:5|should_be_nums',
        ];
        $customMessages = [
            'university.required' => 'لطفاً یک دانشگاه را انتخاب کنید',
            'field.bad_chars' => 'رشته حاوی کاراکتر های غیر مجاز است',
            'link.url' => 'لینک معتبر نیست',
            'chanceGetting.should_be_nums' => 'شانس قبولی فقط می تواند عدد باشد',
            'chanceGetting.min' => 'شانس قبولی حداقل باید 0 باشد',
            'chanceGetting.max' => 'شانس قبولی حداکثر باید 5 باشد',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            session()->flash('error', 'خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $university = new UserUniversity();
        $university->user_id = $request->id;
        $university->university_id = $request->university;
        $university->field = $request->field;
        $university->status = 2;
        if ($request->chanceGetting)
            $university->chance_getting = $request->chanceGetting;
        if ($request->offer)
            $university->offer = 1;
        $university->description = $request->desc;
        if ($request->link)
            $university->link = $request->link;
        if ($university->save())
            session()->flash('success', 'دانشگاه برای کاربر باز شد');
        else
            session()->flash('error', 'خطا در باز کردن دانشگاه برای کاربر');
        return redirect()->back();
    }

    function deleteUserUniversity($id)
    {
        $university = UserUniversity::find($id);
        if ($university->delete())
            session()->flash('success', 'دانشگاه برای کاربر با موفقیت حذف شد');
        else
            session()->flash('error', 'خطا در حذف دانشگاه برای کاربر');
        return redirect()->back();
    }

    function deleteUserTelSupport($id)
    {
        $userTelSupport = UserTelSupport::find($id);
        $telSupport = $userTelSupport->telSupport;
        DB::beginTransaction();
        try {
            $user = $userTelSupport->user;
            $user->charge += $telSupport->price;
            $user->save();
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = $telSupport->price;
            $transaction->status = 1;
            $transaction->pay_type = 1;
            $transaction->save();
            $userTelSupport->delete();
            $telSupport->status = 1;
            $telSupport->save();
            DB::commit();
            session()->flash('success', 'وقت مشاوره برای کاربر با موفقیت حذف شد');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'خطا در حذف وقت مشاوره برای کاربر');
        }
        return redirect()->back();
    }

    function deleteTelSupport($id)
    {
        $telSupport = TelSupport::find($id);
        if ($telSupport->delete()) {
            session()->flash('success', 'وقت مشاوره برای کاربر با موفقیت حذف شد');
            return redirect()->back();
        }
        session()->flash('error', 'خطا در حذف وقت مشاوره برای کاربر');
        return redirect()->back();
    }

    function deleteTelSupportUser($id)
    {
        $telSupport = UserTelSupport::where('tel_support_id', $id)->first();
        if ($telSupport->delete()) {
            session()->flash('success', 'وقت زمان مشاوره برای کاربر با موفقیت حذف شد');
            return redirect()->back();
        }
        session()->flash('error', 'خطا در حذف زمان مشاوره برای کاربر');
        return redirect()->back();
    }

    function userAcceptances(Request $request)
    {
        $user = User::find($request->id);
        $acceptances = $user->acceptances()->orderBy('id', 'DESC')->paginate(10);
        return view('admin.users.acceptancesList', compact('acceptances'))->render();
    }

    function getUserAcceptance(Request $request)
    {
        $acceptance = Acceptance::find($request->id);
        return view('admin.users.showAcceptance', compact('acceptance'))->render();
    }

    function userUniversities(Request $request)
    {
        $user = User::find($request->id);
        $universities = $user->universities()->orderBy('id', 'DESC')->paginate(10);
        return view('admin.users.universitiesList', compact('universities'))->render();
    }

    function userTransactions(Request $request)
    {
        $user = User::find($request->id);
        $transactions = $user->invoices()->orderBy('id', 'DESC')->paginate(10);
        return view('admin.users.transactionsList', compact('transactions'))->render();
    }

    function userTransactionsInformation(Request $request)
    {
        $user = User::find($request->id);
        $transactions = $user->invoices()->orderBy('id', 'DESC')->paginate(10);
        return view('admin.users.information.transactionsList', compact('transactions'))->render();
    }

    function userUserTelSupports(Request $request)
    {
        $user = User::find($request->id);
        $userTelSupports = $user->userTelSupports()->orderBy('id', 'DESC')->paginate(10);
        return view('admin.users.userTelSupportsList', compact('userTelSupports'))->render();
    }

    function userUploads(Request $request)
    {
        $user = User::find($request->id);
        $uploads = $user->uploads()->orderBy('id', 'DESC')->paginate(10);
        return view('admin.users.uploadsList', compact('uploads'))->render();
    }

    function exportUsers(Request $request)
    {

        return Excel::download(new Users($request), 'users.xlsx');
    }

    function deleteUserUniversityLevelStatus($id)
    {
        $university = UserUniversity::find($id);
        $university->status = 2;
        if ($university->save()) {
            session()->flash('success', 'وضعیت اپلای حذف گردید');
        } else
            session()->flash('error', 'خطا در حذف وضعیت اپلای');
        return redirect()->back();
    }

    function setUpdateAcceptance($id)
    {
        $acceptance = Acceptance::where('id', $id)->first();
        return view('admin.users.updateAcceptance')->with('acceptance', $acceptance)->render();
    }

    function saveUserAcceptance($id, Request $request)
    {
        $acceptance = Acceptance::find($id);

        $acceptance->firstname = $request->firstname;
        $acceptance->lastname = $request->lastname;
        $acceptance->phone = $request->mobile;
        $acceptance->birth_date = $request->birth_date;
        $acceptance->embassy_appointment = $request->embassy_appointment;
        $acceptance->embassy_date = $request->embassy_date;
        $acceptance->admittance = $request->admittance;
        $acceptance->diploma_grade_average = $request->diploma_grade_average;
        $acceptance->pre_university_grade_average = $request->pre_university_grade_average;
        $acceptance->field_grade = $request->field_grade;
        $acceptance->is_license_semesters = $request->is_license_semesters;
        $acceptance->field_license = $request->field_license;
        $acceptance->university_license = $request->university_license;
        $acceptance->license_graduated = $request->license_graduated;
        $acceptance->average_license = $request->average_license;
        $acceptance->year_license = $request->year_license;
        $acceptance->total_number_passes = $request->total_number_passes;
        $acceptance->Pass_30_units = $request->Pass_30_units;
        $acceptance->senior_educate = $request->senior_educate;
        $acceptance->field_senior = $request->field_senior;
        $acceptance->university_senior = $request->university_senior;
        $acceptance->average_senior = $request->average_senior;
        $acceptance->year_senior = $request->year_senior;
        $acceptance->another_educate = $request->another_educate;
        $acceptance->military_service = $request->military_service;
        $acceptance->language_favor = $request->language_favor;
        $acceptance->license_language = $request->license_language;
        $acceptance->what_grade_language = $request->what_grade_language;
        $acceptance->what_intent_grade_language = $request->what_intent_grade_language;
        $acceptance->date_intent_grade_language = $request->date_intent_grade_language;
        $acceptance->doc_translate = $request->doc_translate;
        $acceptance->doc_embassy = $request->doc_embassy;
        $acceptance->description = $request->description;
        if ($acceptance->save()) {
            session()->flash('success', 'ویرایش درخواست اخذ پذیرش با موفقیت انجام گردید');
        } else {
            session()->flash('error', 'ویرایش درخواتس اخذ پذیرش با شکست مواجه گردید');
        }
        return redirect()->back();
    }

    function getUserInfo(Request $request)
    {
        $user = User::find($request->userId);
        $extraUniversities=UserExtraUniversity::where('user_id',$request->userId)->get();
        $totalPrice=0;
        foreach ($extraUniversities as $extraPrice){
            $totalPrice +=$extraPrice->extra_price_euro;
        }
        $users = User::where('user_id', $request->userid)->get();
        $balance = 0;
        $balance2 = 0;
        foreach ($users as $item) {
            $invoice = Invoice::where('user_id', $item->id)->where('invoice_type', 'final')->where('payment_status', 'paid')->first();
            if ($invoice) {
                $balance += intval(Pricing::first()->invite_action);
                $balance2 += intval(Pricing::first()->invite_action);
            } else {
                $balance2 += intval(Pricing::first()->invite_action);
            }
        }
//        if ($user->balance - $balance2 > 0)
            $balance = $balance + ($user->balance - $balance2);

        return response()->json([
            'extraUniversities'=>sizeof($extraUniversities),
            'extraUniversitiesTotalPrice'=>$totalPrice,
            'balance' => $balance,
            'userId' => '0',
        ]);
    }
}
