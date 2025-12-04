<?php

namespace App\Exports;

use App\Exports\Sheets\InternalCcmSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class InternalCcmExport implements WithMultipleSheets
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $ecrsCategoryDetailsCollection;

    public function __construct($ecrsCategoryDetailsCollection)
    {
        $this->ecrsCategoryDetailsCollection = $ecrsCategoryDetailsCollection;
    }

    public function sheets(): array{
        $sheets = [];
        $sheets['External Report'] = new InternalCcmSheet($this->ecrsCategoryDetailsCollection);
        return $sheets;
    }
    public function collection()
    {
        return $this->ecrsCategoryDetailsCollection;
    }
}
