<?php

namespace App\ExcelExports;

use App\Models\User;
use App\Providers\JDF;
use App\Models\UserWebinar;
use App\Providers\MyHelpers;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;

class UserWebinars implements FromView
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {

        $user = UserWebinar::Query();
        
                
        $users = $user->where('webinar_id',$this->request->webinarId)->orderBy('id','DESC')->get();

        return view('admin.exports.webinarParticipants', [
            'users' => $users
        ]);
    }
}
