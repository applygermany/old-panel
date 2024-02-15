<?php

namespace App\ExcelExports;

use App\Models\Transaction;
use App\Models\User;
use App\Providers\JDF;
use App\Models\UserWebinar;
use App\Providers\MyHelpers;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;

class Invites implements FromView
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $users = User::withCount('invites')->where('user_id','<>', 0);

        if ($this->request->code)
            $users->where('user_id', 'LIKE', '%' . $this->request->code . '%');
        if ($this->request->user)
            $users->where('user_id', $this->request->user);
        $invites = $users->orderBy('id', 'DESC')->get();

        return view('admin.exports.invites', [
            'invites' => $invites
        ]);
    }
}
