<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class TestExport implements FromCollection
{
    protected $test;

    public function __construct($test)
    {
        $this->test = $test;
    }

    public function collection()
    {
        // Wrap string in a Collection
        return new Collection([
            ['Value'], // column headers
            [$this->test], // data
        ]);
    }
}
