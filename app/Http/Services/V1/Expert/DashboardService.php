<?php

namespace App\Http\Services\V1\Expert;

use App\Models\ChangeRequest;
use App\Models\User;
use App\Providers\MyHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardService
{
    public function uploadImage(Request $request)
    {
        if ($request->file('image')) {
            auth()->guard('api')->user()->touch();
            $folder = '/uploads/avatar/';
            $file = $request->file('image');
            $file->move(public_path().$folder,auth()->guard('api')->id().'.jpg');
            return 1;
        }
        return 0;
    }

    public function changeEmailMobile(Request $request) {
        if($request->newEmail) {
            $request->newEmail = MyHelpers::numberToEnglish($request->newEmail);
            $user = User::where('email',$request->newEmail)->where('id','!=',auth()->guard('api')->id())->first();
            if($user)
                return 2;
        }
        if($request->newMobile) {
            $request->newMobile = MyHelpers::numberToEnglish($request->newMobile);
            if(strlen($request->newMobile) > 10)
                $request->newMobile = substr($request->newMobile,1,10);
            $user = User::where('mobile',$request->newMobile)->where('id','!=',auth()->guard('api')->id())->first();
            if($user)
                return 3;
        }
        $changeRequest = ChangeRequest::where('user_id',auth()->guard('api')->id())->first();
        if(!$changeRequest)
            $changeRequest = new ChangeRequest();
        $changeRequest->user_id = auth()->guard('api')->id();
        if($request->newEmail) {
            $changeRequest->email = $request->newEmail;
            $changeRequest->mobile = null;
        }
        else
            $changeRequest->email = null;
        if($request->newMobile) {
            $changeRequest->mobile = $request->newMobile;
            $changeRequest->email = null;
        }
        else
            $changeRequest->mobile = null;
        $changeRequest->code = bcrypt(12345);
        if($changeRequest->save())
            return 1;
        return 0;
    }

    public function changeEmailMobileVerify(Request $request) {
        $changeRequest = ChangeRequest::where('user_id',auth()->guard('api')->id())->first();
        if($changeRequest) {
            if($changeRequest->email != null) {
                $user = User::where('email',$changeRequest->email)->where('id','!=',auth()->guard('api')->id())->first();
                if($user) {
                    $changeRequest->delete();
                    return 4;
                }
            }
            if($changeRequest->mobile != null) {
                $user = User::where('mobile',$changeRequest->mobile)->where('id','!=',auth()->guard('api')->id())->first();
                if($user) {
                    $changeRequest->delete();
                    return 3;
                }
            }

            if (!Hash::check($request->code , MyHelpers::numberToEnglish($changeRequest->code)))
                return 2;
            if($changeRequest->mobile != null)
                auth()->guard('api')->user()->mobile = $changeRequest->mobile;
            if($changeRequest->email != null)
                auth()->guard('api')->user()->email = $changeRequest->email;
            if(auth()->guard('api')->user()->save()) {
                $changeRequest->delete();
                return 1;
            }
        }
        return 0;
    }

    public function changeEmailMobileResendCode() {
        $changeRequest = ChangeRequest::where('user_id',auth()->guard('api')->id())->first();
        if($changeRequest) {
            $changeRequest->code = bcrypt(12345);
            if($changeRequest->save())
                return 1;
        }
        return 0;
    }

    public function updatePassword(Request $request) {
        $request->oldPassword = MyHelpers::numberToEnglish($request->oldPassword);
        if(!Hash::check($request->oldPassword, MyHelpers::numberToEnglish(auth()->guard('api')->user()->password)))
            return 2;
        auth()->guard('api')->user()->password = bcrypt($request->newPassword);
        if(auth()->guard('api')->user()->save())
            return 1;
        return 0;
    }

    public function changeDarkMode() {
        if(auth()->guard('api')->user()->darkmode == 1)
            auth()->guard('api')->user()->darkmode = 0;
        else
            auth()->guard('api')->user()->darkmode = 1;
        auth()->guard('api')->user()->save();
        return 1;
    }

    function getApply(User $user){
        $select = "select `nag_user_universities`.`user_id`,
`nag_user_universities`.`id`,
`nag_user_universities`.`university_id`,
`nag_user_universities`.`field`,
`nag_user_universities`.chance_getting,
`nag_user_universities`.`description`,
`nag_user_universities`.`offer`,
`nag_user_universities`.`link`,
`nag_user_universities`.`status`,
`nag_user_universities`.`level_status`,
`nag_universities`.`title`,
`nag_universities`.`city`,
`nag_universities`.`state`,
`nag_universities`.`geographical_location`,
`nag_universities`.`city_crowd`,
`nag_universities`.`cost_living`
from `nag_user_universities`";

        $select .= " inner join `nag_users` on `nag_user_universities`.`user_id` = `nag_users`.`id`";
        $select .= " inner join `nag_universities` on `nag_user_universities`.`university_id` = `nag_universities`.`id`";
        $select .= " where `nag_user_universities`.`user_id` = ".$user->id;
        $select .= " and `nag_user_universities`.`status` = 1";
        $select .= ' order by nag_user_universities.id desc';
        $universities = DB::select($select);

        return $universities;
    }
}
