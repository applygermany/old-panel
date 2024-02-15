<?php
namespace App\Exports\Sheets;

use App\Invoice;
use App\Models\Motivation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;

class MotivationQuery implements FromQuery, WithTitle
{
    use Exportable;
    public $id;
    public $title;
    public function __construct($title, $id)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function query()
    {
        return Motivation::where("id", $this->id);
    }
    public function title(): string
    {
        return $this->title;
    }
}