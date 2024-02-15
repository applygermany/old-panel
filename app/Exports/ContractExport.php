<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class ContractExport implements FromCollection
{
    protected $data;

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function collection()
    {
        return collect($this->data);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings() :array
    {
        return [
            'FirstName',
            'LastName',
            'Mobile',
            'Date',
        ];
    }
}