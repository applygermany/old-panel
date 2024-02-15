<?php

namespace App\Exports;

use App\Exports\Sheets\MotivationSheet;
use App\Models\Motivation;

use App\Exports\Sheets\MotivationQuery;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MotivationExport implements WithMultipleSheets
{
    use Exportable;
    public $id;
    public function __construct($id)
    {
        $this->id = $id;
    }


    /**
     * @return array
     */
    public function sheets(): array
    {
        

        $sheets = [];
        $motivation = Motivation::find($this->id);
        if(!$motivation){
            abort(404);
        }
        $sheets[] = new MotivationSheet("اطلاعات", "admin.motivations.exports", $motivation);
        $sheets[] = new MotivationSheet("دانشگاه ها", "admin.motivations.universitiesList", $motivation);

        

        return $sheets;
    }
}