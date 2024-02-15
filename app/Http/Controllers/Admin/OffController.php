<?php

namespace App\Http\Controllers\Admin;

use App\ExcelExports\Discount;
use App\ExcelExports\DiscountCode;
use App\ExcelExports\DiscountCodeInviter;
use App\ExcelExports\Transactions;
use App\Models\DiscountInviter;
use App\Models\Invoice;
use App\Models\Off;
use App\Models\User;
use App\Providers\JDF;
use App\Providers\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Providers\MyHelpers;
use Maatwebsite\Excel\Facades\Excel;

class OffController extends Controller
{
    function offs()
    {
        $offs = Off::orderBy('id', 'DESC')->paginate(10);
        $users = User::select('id', 'firstname', 'lastname')->get();
        return view('admin.financials.offs', compact('offs', 'users'));
    }

    function getOffs(Request $request)
    {
        $off = Off::Query();
        if ($request->searchUser)
            $off->where('user_id', $request->searchUser);
        if ($request->searchCode)
            $off->where('code', 'LIKE', '%' . $request->searchCode . '%');
        $offs = $off->orderBy('id', 'DESC')->paginate(10);
        return view('admin.financials.listOffs', compact('offs'))->render();
    }

    function saveOff(Request $request)
    {
        $rules = [
            'discount' => 'required|should_be_nums',
            'code' => 'required',
            'max' => 'should_be_nums',
            'userMax' => 'should_be_nums',
        ];

        $customMessages = [
            'discount.required' => 'مقدار تخفیف را وارد کنید',
            'code.required' => 'کد تخفیف را وارد کنید',
            'discount.should_be_nums' => 'مقدار تخفیف معتبر نیست',
            'max.should_be_nums' => 'باید عدد باشد',
            'userMax.should_be_nums' => 'باید عدد باشد',
        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            session()->flash('error', 'خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $off = new Off();
        $off->discount = $request->discount;
        $off->discount_type = $request->type;
        $off->type = $request->off_type;
        $off->maximum_usage = $request->max ? $request->max : 0;
        $off->user_usage = $request->userMax ? $request->userMax : 1;
        if ($request->code)
            $off->code = $request->code;
        else
            $off->code = $this->RandomString();
        if ($request->user != null)
            $off->user_id = $request->user;
        $off->start_date = MyHelpers::numberToEnglish($request->start_date);
        $off->end_date = MyHelpers::numberToEnglish($request->end_date);

        if ($off->save()) {

            if ($request->user != null) {
                $notif = (new Notification("new_discount_code", [
                    $off->code,
                    $off->start_date,
                    $off->end_date,
                    $off->type_title,
                ]))->send($request->user);
            }

            session()->flash('success', 'مبلغ تخفیف ثبت شد');
        } else
            session()->flash('error', 'خطا در ثبت مبلغ تخفیف');
        return redirect()->back();
    }

    function editOff($id)
    {
        $off = Off::find($id);
        $users = User::all();
        $options = "<option value=''>انتخاب کنید</option>";
        foreach ($users as $user) {
            $selected = '';
            if ($off->user_id == $user->id)
                $selected = "selected";
            $options .= '<option value="' . $user->id . '" ' . $selected . '>' . $user->name . ' - ' . $user->mobile . ' / ' . $user->email . '</option>';
        }
        $data = csrf_field() . '
<input name="editId" type="hidden" id="editId" value="' . $off->id . '">
<div class="row">
    <div class="form-group float-label col-3">
        <label for="editDiscount" class="header-label">مبلغ تخفیف</label>
        <input name="editDiscount" type="text" class="form-control" id="editDiscount" value="' . $off->discount . '">
    </div>
    <div class="form-group float-label col-9">
        <label for="editUser" class="header-label">کاربر</label>
        <select name="editUser" id="editUser" class="form-control">
            ' . $options . '
        </select>
    </div>
     <div class="form-group float-label col-6">
                                        <div class="form-group float-label">
                                            <label class="header-label" for="editType">نوع تخفیف</label>
                                            <select name="editType" class="form-control form-select-sm" id="editType">
                                                <option ' . ($off->discount_type == 1 ? "selected" : "") . ' value="1">درصدی</option>
                                                <option ' . ($off->discount_type == 2 ? "selected" : "") . ' value="2">مقداری</option>
                                            </select>
                                        </div>
                                    </div>
                                       <div class="form-group float-label col-6">
                                    
                                         <div class="form-group float-label">
                                            <label class="header-label" for="editMax">سقف مجاز استفاده</label>
                                            <input type="text" name="editMax" class="form-control form-control-sm" id="editMax"  value="' . $off->maximum_usage . '" placeholder="تعداد">
                                            
                                        </div>
                                    </div>
   
    <div class="form-group float-label col-6">
        <label for="editDate" class="header-label">تاریخ</label>
        <input name="editDate" type="text" class="form-control date" id="editDate" value="' . MyHelpers::numberToPersian($off->end_date) . '">
    </div>
</div>';
        return $data;
    }

    function updateOff(Request $request)
    {
        $rules = [
            'editDiscount' => 'required|should_be_nums',
            'editCode' => 'nullable|max:9',
            'editMax' => 'should_be_nums',
        ];

        $customMessages = [
            'editDiscount.required' => 'مبلغ تخفیف را وارد کنید',
            'editDiscount.should_be_nums' => 'مبلغ تخفیف معتبر نیست',

            'editCode.max' => 'کد حداکثر باید 9 کلمه باشد',
        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()]);

        $off = Off::find($request->editId);
        if ($request->editDiscount)
            $off->discount = $request->editDiscount;
        if ($request->editUser != null)
            $off->user_id = $request->editUser;
        else
            $off->user_id = 0;

        $off->maximum_usage = $request->editMax;
        if ($request->editType)
            $off->discount_type = $request->editType;
        if ($request->editDate)
            $off->end_date = MyHelpers::numberToEnglish($request->editDate);
        if ($off->save())
            return 1;
        return 0;
    }

    function activateOff($id)
    {
        $off = Off::find($id);
        if ($off->status == 2)
            $off->status = 1;
        else
            $off->status = 2;
        $off->save();
        return $off->status;
    }

    function deleteOff($id)
    {
        $off = Off::find($id);

        if ($off->delete())
            session()->flash('success', 'مبلغ تخفیف حذف شد');
        else
            session()->flash('error', 'خطا در حذف مبلغ تخفیف');
        return redirect()->back();
    }

    function RandomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 6; $i++) {
            $randstring .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randstring;
    }

    function checkCode(Request $request)
    {
        $date = MyHelpers::dateToJalali2(date("Y-m-d"));
        $off = Off::where('type', $request->type)->where('code', $request->code)->where('status', 1)->first();
        if ($off) {
            $startDate = MyHelpers::numberToEnglish($off->start_date);
            $endDate = MyHelpers::numberToEnglish($off->end_date);
            if ($off->user_id === 0) {
                if ($date >= $startDate && $date <= $endDate) {
                    if ($off->current_usage < $off->maximum_usage) {
                        $invoice = Invoice::where('user_id', $request->userId)->where('discount_code', $request->code)->count();
                        if ($invoice < $off->user_usage) {
                            return response([
                                'status' => 1,
                                'type' => $off->discount_type === 1 ? 'percent' : 'fixed',
                                'amount' => $off->discount,
                            ]);
                        }
                    }
                }
            } else {
                if ($off->user_id === $request->userId) {
                    if ($date >= $startDate && $date <= $endDate) {
                        if ($off->current_usage < $off->maximum_usage) {
                            $invoice = Invoice::where('user_id', $request->userId)->where('discount_code', $request->code)->count();
                            if ($invoice < $off->user_usage) {
                                return response([
                                    'status' => 1,
                                    'type' => $off->discount_type === 1 ? 'percent' : 'fixed',
                                    'amount' => $off->discount,
                                ]);
                            }
                        }
                    }
                }
            }
        }
        return response([
            'status' => 0,
        ]);
    }

    function export(Request $request)
    {
        $off = Off::query();
        if ($request->exportUser) {
            $off->where('user_id', $request->exportUser);
        }
        if ($request->exportCode) {
            $off->where('code', $request->exportCode);
        }
        $offs = $off->orderBy('id', 'DESC')->get();
        return Excel::download(new Discount($offs), 'discount.xlsx');
    }

    function exportOff($id)
    {
        $off = Off::find($id);
        $invoices = Invoice::where('discount_code', $off->code)->get();
        return Excel::download(new DiscountCode($invoices), 'discountCode.xlsx');
    }

    function inviterCodes()
    {
        $offs = DiscountInviter::orderBy('id', 'DESC')->paginate(10);
        $users = User::select('id', 'firstname', 'lastname')->get();
        $inviter = User::select('id', 'firstname', 'lastname')->whereIn('level', [3, 4, 5, 7])->get();
        return view('admin.financials.inviter.index', compact('offs', 'users', 'inviter'));
    }

    function saveOffInviter(Request $request)
    {
        $rules = [
            'discount' => 'required|should_be_nums',
            'code' => 'required',
            'max' => 'should_be_nums',
//            'userMax' => 'should_be_nums',
        ];

        $customMessages = [
            'discount.required' => 'مقدار تخفیف را وارد کنید',
            'code.required' => 'کد تخفیف را وارد کنید',
            'discount.should_be_nums' => 'مقدار تخفیف معتبر نیست',
            'max.should_be_nums' => 'باید عدد باشد',
//            'userMax.should_be_nums' => 'باید عدد باشد',
        ];

        $validator = validator()->make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            session()->flash('error', 'خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $off = new DiscountInviter();
        $off->discount = $request->discount;
        $off->discount_code = $request->discount_code;
        $off->discount_type = $request->type;
        $off->type = $request->off_type;
        $off->maximum_usage = $request->max ? $request->max : 0;
        $off->user_usage = $request->userMax ? $request->userMax : 1;
        if ($request->code)
            $off->code = $request->code;
        else
            $off->code = $this->RandomString();
        if ($request->user != null)
            $off->user_id = $request->user;
        $off->start_date = MyHelpers::numberToEnglish($request->start_date);
        $off->end_date = MyHelpers::numberToEnglish($request->end_date);

        if ($off->save()) {

            if ($request->user != null) {
                $notif = (new Notification("new_discount_code", [
                    $off->code,
                    $off->start_date,
                    $off->end_date,
                    $off->type_title,
                ]))->send($request->user);
            }

            session()->flash('success', 'مبلغ تخفیف ثبت شد');
        } else
            session()->flash('error', 'خطا در ثبت مبلغ تخفیف');
        return redirect()->back();
    }

    function activateInviterOff($id)
    {
        $off = DiscountInviter::find($id);
        if ($off->status == 'deactive')
            $off->status = 'active';
        else
            $off->status = 'deactive';
        $off->save();
        return $off->status;
    }

    function deleteOffInviter($id)
    {
        $off = DiscountInviter::find($id);

        if ($off->delete())
            session()->flash('success', 'مبلغ تخفیف حذف شد');
        else
            session()->flash('error', 'خطا در حذف مبلغ تخفیف');
        return redirect()->back();
    }

    function searchInviterCodes(Request $request)
    {
        $off = DiscountInviter::Query();
        if ($request->searchUser)
            $off->where('user_id', $request->searchUser);
        if ($request->searchCode)
            $off->where('code', 'LIKE', '%' . $request->searchCode . '%');
        $offs = $off->orderBy('id', 'DESC')->paginate(10);
        $users = User::select('id', 'firstname', 'lastname')->get();
        $inviter = User::select('id', 'firstname', 'lastname')->whereIn('level', [3, 4, 5, 7])->get();
        return view('admin.financials.inviter.list', compact('offs', 'users', 'inviter'));
    }

    function exportOffInviter($id)
    {
        $off = DiscountInviter::find($id);
        $invoices = Invoice::where('discount_code_inviter', $off->code)->get();
        return Excel::download(new DiscountCodeInviter($invoices), 'discountCode.xlsx');
    }

    function exportInviter(Request $request)
    {
        $off = DiscountInviter::query();
        if ($request->exportUser) {
            $off->where('user_id', $request->exportUser);
        }
        if ($request->exportCode) {
            $off->where('code', $request->exportCode);
        }
        $offs = $off->orderBy('id', 'DESC')->get();
        return Excel::download(new DiscountInviter($offs), 'discount.xlsx');
    }

    function checkCodeInviter(Request $request)
    {
        $date = MyHelpers::dateToJalali2(date("Y-m-d"));
        $off = DiscountInviter::where('type', $request->type)->where('code', $request->code)->orWhere('discount_code', $request->code)->where('status', 'active')->first();
        if ($off) {
            $startDate = MyHelpers::numberToEnglish($off->start_date);
            $endDate = MyHelpers::numberToEnglish($off->end_date);
            if ($off->user_id === 0) {
                if ($date >= $startDate && $date <= $endDate) {
                    if ($off->current_usage < $off->maximum_usage) {
                        $invoice = Invoice::where('user_id', $request->userId)->where('discount_code_inviter', $off->code)->count();
                        if ($invoice < $off->user_usage) {
                            $createdAt = MyHelpers::dateToJalali2($invoice->user->created_at->format("Y-m-d"));
                            $packageAt = MyHelpers::dateToJalali2($invoice->user->package_at->format("Y-m-d"));
                            if (($createdAt >= $startDate && $createdAt <= $endDate) || ($packageAt >= $startDate && $packageAt <= $endDate)) {
                                return response([
                                    'status' => 1,
                                    'type' => $off->discount_type === 1 ? 'percent' : 'fixed',
                                    'amount' => $off->discount,
                                ]);
                            }
                        }
                    }
                }
            } else {
                if ($off->user_id === $request->userId) {
                    if ($date >= $startDate && $date <= $endDate) {
                        if ($off->current_usage < $off->maximum_usage) {
                            $invoice = Invoice::where('user_id', $request->userId)->where('discount_code_inviter', $request->code)->count();
                            if ($invoice < $off->user_usage) {
                                $createdAt = MyHelpers::dateToJalali2($invoice->user->created_at->format("Y-m-d"));
                                $packageAt = MyHelpers::dateToJalali2($invoice->user->package_at->format("Y-m-d"));
                                if (($createdAt >= $startDate && $createdAt <= $endDate) || ($packageAt >= $startDate && $packageAt <= $endDate)) {
                                    return response([
                                        'status' => 1,
                                        'type' => $off->discount_type === 1 ? 'percent' : 'fixed',
                                        'amount' => $off->discount,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        return response([
            'status' => 0,
        ]);
    }

}
