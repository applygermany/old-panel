<?php

namespace App\Http\Services\V1\Expert;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApplyService
{
    public function getApply(User $user)
    {
        $logged_user = auth()->guard('api')->user();
        if($logged_user->users()->where('user_id' , $user->id)->first()){
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
        }else{
            return 0;
        }
    }

    public function uploadApplyFile(Request $request)
    {
        if ($request->file('file')) {
			
            $folder = '/uploads/applies/'.$request->userId.'/';
            $file = $request->file('file');
	
	        $filecontent = file_get_contents($file);
	        if (preg_match("/^%PDF-1.7/", $filecontent)) {
		        $file->move(public_path() . $folder, $request->id . '.pdf');
	        } else {
				return 0;
	        }
        }
        return 1;
    }

    public function deleteApplyFile(Request $request)
    {
        $logged_user = auth()->guard('api')->user();

        if($logged_user->users()->where('user_id' , $request->userId)->first()){
            if (is_file(public_path('uploads/applies/' .$request->userId.'/'. $request->id . '.pdf')))
                unlink(public_path('uploads/applies/' .$request->userId.'/'. $request->id . '.pdf'));
            return 1;
        }else{
            return 0;
        }
    }
}
