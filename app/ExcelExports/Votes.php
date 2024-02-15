<?php

namespace App\ExcelExports;

use App\Models\User;
use App\Providers\JDF;
use App\Providers\MyHelpers;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;

class Votes implements FromView
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {

     
        return view('admin.exports.users', [
            'users' => $users
        ]);
    }
}
