<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ChangeControlManagementExport implements FromArray,
// WithStyles,
 WithEvents
{
    public function array(): array
    {
        return [
            ["PRICON MICROELECTRONICS, INC.", "", "", "", "", "", "PPS-101-018"],
            ["OPERATIONS DIVISION"],
            ["CHANGE CONTROL APPLICATION REPORT"],
            ["SECTION NAME", "", "PRODUCT LINE", "", "DEVICE NAME", "", ""],
            ["PART NAME", "", "PART CODE", "", "CUSTOMER", "", ""],
            ["DATE OF APPLICATION", "", "", "", "", "", ""],
            ["4M Change / 1E", "☐ Man  ☐ Machine/Tools  ☐ Material  ☐ Method / Environment", "", "", "Document Affected", "", ""],
            ["Content (Pls. state):", "", "", "", "☐ QC Process Flow Chart", "", ""],
            ["", "", "", "", "☐ Packaging Specification", "", ""],
            ["", "", "", "", "☐ Part/Product Specification", "", ""],
            ["", "", "", "", "☐ Assembly Drawing", "", ""],
            ["", "", "", "", "☐ SG / Assembly Manual", "", ""],
            ["", "", "", "", "☐ Others (pls. specify):", "", ""],
            ["Target date of implementation:", "", "", "", "With attachment: ☐ Yes ☐ No", "", ""],
            ["Title of attachment:", "", "", "", "Actual Sample Attached: ☐ Yes ☐ No", "", ""],
            ["", "", "", "", "Qty: ______ pcs.", "", ""],
            ["BEFORE", "AFTER", "REASON FOR APPLICATION", "", "", "", ""],
            ["", "", "", "", "", "", ""],
            ["Prepared by:", "", "", "", "Checked by:", "", ""],
            ["Effect on Man (By Production)", "", "", "Assessed by:", "", "Checked by: Section Head", ""],
            ["Effect on Machine/Tools", "", "", "Assessed by:", "", "Checked by: Section Head", ""],
            ["Effect on Method/Environment", "", "", "Assessed by:", "", "Checked by: Section Head", ""],
            ["Effect on Materials", "", "", "Assessed by:", "", "Checked by: Section Head", ""],
            ["Line QC Remarks", "", "", "Assessed by:", "", "Checked by: Section Head", ""],
            ["PMI Approval", "", "", "", "", "", ""],
            ["QC Head", "", "Operations Head", "", "QAD Head", "", ""],
            ["YEC Approval? ☐ Need ☐ No Need", "", "", "", "Final Disposition: ☐ Accept ☐ Reject", "", ""],
            ["REMARKS:", "", "", "", "", "", ""],
            ["USE THIS PORTION IF DISPOSITION IS ACCEPTED WITH CONDITION", "", "", "", "", "", ""],
            ["Action/s Required", "Target Date", "In-Charge", "Result", "", "", ""],
            ["", "", "", "", "", "", ""],
            ["QAD SIGNATURE:", "", "", "", "", "", ""],
        ];
    }

    public function title(): string
    {
        return 'Change Report';
    }
    // public function styles(Worksheet $sheet)
    // {
    //     $sheet->getStyle('A1:G40')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
    //     $sheet->getStyle('A1:G40')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
    //     $sheet->getStyle('A1:G3')->getFont()->setBold(true);
    //     return [];
    // }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Apply thin border to A1:G40
                $sheet->getStyle('A1:G40')->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // Bold for headers
                $sheet->getStyle('A1:G3')->getFont()->setBold(true);

                // Merge example cells (adjust more as needed)
                $sheet->mergeCells('A1:F1'); // Title
                $sheet->mergeCells('A2:F2'); // Division
                $sheet->mergeCells('A3:F3'); // Form Title

                $sheet->getColumnDimension('A')->setWidth(30);
                $sheet->getColumnDimension('B')->setWidth(25);
                $sheet->getColumnDimension('C')->setWidth(25);
                $sheet->getColumnDimension('D')->setWidth(25);
                $sheet->getColumnDimension('E')->setWidth(25);
                $sheet->getColumnDimension('F')->setWidth(25);
                $sheet->getColumnDimension('G')->setWidth(25);
            }
        ];
    }
}
