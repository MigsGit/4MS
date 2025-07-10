<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ChangeControlManagementExport implements FromArray, WithStyles
{
    // protected $test;

    // public function __construct($test)
    // {
    //     $this->test = $test;
    // }

    // public function collection()
    // {
    //     // Wrap string in a Collection
    //     return $this->test;

    // }
    public function array(): array
    {
        return [
            ['PRICON MICROELECTRONICS, INC.', '', '', '', '', '', 'PPS-01-018'],
            ['OPERATIONS DIVISION'],
            ['CHANGE CONTROL APPLICATION REPORT'],
            ['SECTION NAME', '', '', '', '', 'DOCUMENT ATTACHED'],
            // Sample input fields and rows
            ['PRODUCT LINE', '', '', '', '', '□ QC Process Flow Chart'],
            ['DEVICE NAME', '', '', '', '', '□ Packaging Specification'],
            ['PART NAME', '', '', '', '', '□ Part / Product Specification'],
            ['PART CODE', '', '', '', '', '□ Assembly Drawing'],
            ['CUSTOMER', '', '', '', '', '□ SS / Assembly Manual'],
            ['DATE OF APPLICATION', '', '', '', '', '□ Others (pls. specify)'],
            // Add more rows as needed
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        $sheet->mergeCells('A3:F3');
        $sheet->mergeCells('G1:G3');

        $sheet->getStyle('A1:G20')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle('A1:G3')->getFont()->setBold(true);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal('center');

        return [
            'A1' => ['font' => ['bold' => true, 'size' => 12]],
            'A3' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}
