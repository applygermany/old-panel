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

class Transactions implements FromView
{
    public $invoices;

    public function __construct($invoices)
    {
        $this->invoices = $invoices;
    }

    public function view(): View
    {
        return view('admin.financials.invoices.partials.export', [
            'invoices' => $this->invoices
        ]);
    }
}
