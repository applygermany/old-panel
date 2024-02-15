<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Setting;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    function version()
    {
        $support_version = Option::where('name', 'support_version')->first()->value;
        $user_version = Option::where('name', 'user_version')->first()->value;
        $writer_version = Option::where('name', 'writer_version')->first()->value;
        return view('admin.version.index', compact('support_version', 'writer_version', 'user_version'));
    }

    function editVersion(Request $request){
        $rules = [
            'support_version'   => 'required',
            'user_version' => 'required',
            'writer_version' => 'required',
        ];
        $customMessages = [
            'support_version.required'       => 'ورود ورژن پنل کارشناس الزامی است',
            'user_version.required'       => 'ورود ورژن پنل کاربر الزامی است',
            'writer_version.required'       => 'ورود ورژن پنل کاربر الزامی است',
        ];
        $validator = validator()->make($request->all(), $rules, $customMessages);
        if ($validator->fails())
            return response()->json([
                'status' => 422,
                'msg'    => 'خطا در مقادیر ورودی',
                'errors' => $validator->errors(),
            ]);

        $support_version = Option::where('name', 'support_version')->first();
        $support_version->value = $request->support_version;
        $support_version->save();

        $user_version = Option::where('name', 'user_version')->first();
        $user_version->value = $request->user_version;
        $user_version->save();

        $writer_version = Option::where('name', 'writer_version')->first();
        $writer_version->value = $request->writer_version;
        $writer_version->save();

        session()->flash('success','ثبت ورژن با موفقیت انجام گردید');
        return redirect()->back();
    }
}
