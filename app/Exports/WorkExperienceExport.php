<?php

namespace App\Exports;

use App\Exports\Sheets\ResumeSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class WorkExperienceExport implements FromCollection
{
    use Exportable;
    public $telSupports;
    public function __construct($telSupports)
    {
        $this->telSupports = $telSupports;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->telSupports);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings() :array
    {
        return [
            'کاربر',
            'تعداد مشاوره',
            'تاریخ ویژه',
            'ترم',
        ];
    }
}
