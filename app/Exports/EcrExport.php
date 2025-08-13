<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class EcrExport implements FromCollection
{
    protected $ecr;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($ecr) {
        $this->ecr = $ecr;
    }
    public function collection()
    {
        return $this->ecr;
    }
}
