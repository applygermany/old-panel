<?php

namespace App\ExcelExports;

use App\Models\User;
use App\Providers\JDF;
use App\Models\UserWebinar;
use App\Providers\MyHelpers;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;

class ContractExport implements FromView
{
    public $contracts;
    public $categories;

    public function __construct($contracts, $categories)
    {
        $this->contracts = $contracts;
        $this->categories = $categories;
    }

    public function view(): View
    {
        return view('admin.reports.exports.contracts', [
            'contracts' => $this->contracts,
            'categories' => $this->categories
        ]);
    }
}
