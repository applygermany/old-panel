<?php
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Providers\MyHelpers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    function loginForm() {
        return view('admin.loginForm');
    }

    function login(Request $request) {
        $rules = [
            'emailMobile'=>'required|bad_chars',
            'password'=>'required'
        ];

        $customMessages = [
            'emailMobile.required' => 'ورود ایمیل/موبایل الزامی است',
            'emailMobile.bad_chars' => 'ایمیل/موبایل معتبر نیست',

            'password.required' => 'ورود گذرواژه الزامی است'
        ];

        $validator = validator()->make($request->all(),$rules,$customMessages);

        if ($validator->fails())
        {
            session()->flash('error','خطا در ورودی ها');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $email = explode("@",$request->emailMobile);
        if(count($email) > 1)
            $user = User::where('email',$request->emailMobile)->where('level',4)->where('status',1)->where('verified',1)->first();
        else {
            if(strlen($request->emailMobile) > 10)
                $request->emailMobile = MyHelpers::numberToEnglish(substr($request->emailMobile,1,10));
            $user = User::where('mobile',$request->emailMobile)->where('level',4)->where('status',1)->where('verified',1)->first();
        }

        if($user) {
            if(!Hash::check($request->password, $user->password))
            {
                session()->flash('error','گذرواژه اشتباه است');
                return redirect()->back();
            }
            session()->flash('token',auth()->guard('api')->login($user));
            Auth::login($user,true);
            $user->last_login = time();
            $user->save();
            return redirect()->route('admin.dashboard');
        } else {
            session()->flash('error', 'حساب کاربری یافت نشد یا غیر فعال است !');
            return redirect()->back();
        }
    }
}
