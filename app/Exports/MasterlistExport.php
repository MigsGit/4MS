<?php

namespace App\Exports;

use App\Exports\Sheets\MasterlistSheet;
use Maatwebsite\Excel\Concerns\FromCollection;

class MasterlistExport implements FromCollection
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
        $sheets['Masterlist'] = new MasterlistSheet($this->ecrsCategoryDetailsCollection);
        return $sheets;
    }
    public function collection()
    {
        return $this->ecrsCategoryDetailsCollection;
    }
}
