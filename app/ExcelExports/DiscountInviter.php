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

class DiscountInviter implements FromView
{
    public $offs;

    public function __construct($offs)
    {
        $this->offs = $offs;
    }

    public function view(): View
    {
        return view('admin.financials.inviter.all', [
            'offs' => $this->offs
        ]);
    }
}
