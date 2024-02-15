<?php
namespace App\Http\Controllers\Admin;

use App\ExcelExports\Invites;
use App\ExcelExports\Transactions;
use App\Models\User;
use App\Models\UserWebinar;
use App\Models\Webinar;
use App\Providers\JDF;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;

class InviteController extends Controller
{
    function invites() {
        $allUsers = User::where('level', 1)->get();
        $users = User::withCount('invites')->where('user_id','<>', 0)->paginate(10);
        return view('admin.invites.invites',compact('users', 'allUsers'));
    }
    
    function getInvites(Request $request)
    {
        $users = User::withCount('invites')->where('user_id','<>', 0);
        if ($request->searchUser)
            $users->where('id', $request->searchUser);
        if ($request->searchCode)
            $users->where('user_id', 'LIKE', '%' . $request->searchCode . '%');
        if ($request->searchUserInviter)
            $users->where('user_id', $request->searchUserInviter);
     
        $allUsers = User::where('level', 1)->get();
        $users = $users->orderBy('id', 'DESC')->paginate(10);
        return view('admin.invites.list', compact('users', 'allUsers'))->render();
    }

    function exportInvites(Request $request)
    {
        return Excel::download(new Invites($request), 'invites.xlsx');
    }
}
