<?php

namespace App\Exports\Sheets;
use Carbon\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class ChangeControlManagementSheet implements
FromArray,
WithEvents
{

    protected $ecrsCategoryDetailsCollection;

    public function __construct($ecrsCategoryDetailsCollection)
    {
        $this->ecrsCategoryDetailsCollection = $ecrsCategoryDetailsCollection;
    }

    public function array(): array
    {
        return [[]];
    }

    public function registerEvents(): array
    {
        $ecrsCategoryDetailsCollection = $this->ecrsCategoryDetailsCollection;

        return [
            AfterSheet::class => function (AfterSheet $event) use($ecrsCategoryDetailsCollection) {
                $sheet = $event->sheet->getDelegate();
                // $ecrsCategoryDetailsCollection->ecr_no": "1",
                // $ecrsCategoryDetailsCollection->category": "Method",
                // $ecrsCategoryDetailsCollection->internal_external": "External",
                // $ecrsCategoryDetailsCollection->customer_name": "test",
                // $ecrsCategoryDetailsCollection->part_no": "test",
                // $ecrsCategoryDetailsCollection->part_name": "test",
                // $ecrsCategoryDetailsCollection->device_name": "test",
                // $ecrsCategoryDetailsCollection->product_line": "test",
                // $ecrsCategoryDetailsCollection->section": "test",
                // $ecrsCategoryDetailsCollection->customer_ec_no": "test",
                // === HEADER
                $sheet->mergeCells('A1:F1')->setCellValue('A1', 'PRICON MICROELECTRONICS, INC.');
                $sheet->mergeCells('A2:F2')->setCellValue('A2', 'OPERATIONS DIVISION');
                $sheet->mergeCells('C3:I4')->setCellValue('C3', 'CHANGE CONTROL APPLICATION REPORT');
                $sheet->mergeCells('K1:L1')->setCellValue('K1', 'PPS-101-018');
                $sheet->mergeCells('J4:L4')->setCellValue('J4', 'Control Number');
                $sheet->setCellValue('J5', $ecrsCategoryDetailsCollection['ecrDetails']->ecr_no);
                // === SECTION INFO
                $sheet->setCellValue('A6', 'SECTION NAME');
                $sheet->setCellValue('A7', 'PRODUCT LINE');
                $sheet->setCellValue('A8', 'DEVICE NAME');
                $sheet->setCellValue('A9', 'PART NAME');
                $sheet->setCellValue('A10', 'PART CODE');
                $sheet->setCellValue('A11', 'CUSTOMER');
                $sheet->setCellValue('A12', 'DATE OF APPLICATION');
                $sectionCol = "C";
                $startSectionRow = "6";
                // === SECTION DATA
                $section = [
                    $ecrsCategoryDetailsCollection['ecrDetails']->section,
                    $ecrsCategoryDetailsCollection['ecrDetails']->product_line,
                    $ecrsCategoryDetailsCollection['ecrDetails']->device_name,
                    $ecrsCategoryDetailsCollection['ecrDetails']->part_name,
                    $ecrsCategoryDetailsCollection['ecrDetails']->part_no,
                    $ecrsCategoryDetailsCollection['ecrDetails']->customer_name,
                    $ecrsCategoryDetailsCollection['ecrDetails']->date_of_request,
                ];
                foreach ($section as $index => $label) {
                    $sheet->setCellValue($sectionCol . ($startSectionRow + $index), $label);
                }
                // === 4M CHANGE & DOCUMENTS
                $sheet->setCellValue('A14', '4M Change / 1E');
                $categoryCol = "B";
                $categoryRow = "14";
                // === SECTION DATA
                $isCategory = $ecrsCategoryDetailsCollection['ecrDetails']->category ?? "";
                $category = [
                    $isCategory === "Man" ? '☑ Man' :'☐ Man',
                    $isCategory === "Machine" ? '☑ Machine/Tools' :'☐ Machine/Tools',
                    $isCategory === "Material" ? '☑ Material' :'☐ Material',
                    $isCategory === "Method" ? '☑ Method' :'☐ Method',
                    $isCategory === "Environment" ? '☑ Environment' :'☐ Environment',
                ];
                for ($i=0; $i < count($category); $i++) {
                    $sheet->setCellValue($categoryCol. $categoryRow, $category[$i]); $categoryCol++;
                }
                $sheet->mergeCells('G6:L6')->setCellValue('G6', 'Document Affected');
                // === Document Type
                $docTypes = [
                    '☐ QC Process Flow Chart',
                    '☐ Packaging Specification',
                    '☐ Part/Product Specification',
                    '☐ Assembly Drawing',
                    '☐ SG / Assembly Manual',
                ];
                $docTypesCol = "G";
                $docTypesRow = 8;
                $sheet->setCellValue($docTypesCol.$docTypesRow, '☐ Others (pls. specify)');
                foreach ($docTypes as $index => $label) {
                   $sheet->setCellValue($docTypesCol . ($docTypesRow + $index), $label);
                }


                // // === Target Date and Attachment
                $sheet->setCellValue('G14', 'Target date of implementation:');
                $sheet->setCellValue('G15', 'With attachment:');
                $sheet->setCellValue('J15', '☐ Yes');
                $sheet->setCellValue('K15', '☐ No');
                $sheet->setCellValue('G16', 'Title of attachment:');
                $sheet->setCellValue('G19', 'Actual Sample Attached:');
                $sheet->setCellValue('J19', '☐ Yes');
                $sheet->setCellValue('K19', 'Qty: ______ pcs.');
                $sheet->setCellValue('J20', '☐ No');

                // // === BEFORE/AFTER
                $sheet->mergeCells('A21:C21')->setCellValue('A21', 'BEFORE');
                $sheet->mergeCells('D21:F21')->setCellValue('D21', 'AFTER');
                $sheet->mergeCells('G21:L21')->setCellValue('G21', 'REASON FOR APPLICATION');

                $sheet->setCellValue('G26', 'Prepared by:');
                $sheet->setCellValue('J26', 'Checked by:');
                $sheet->mergeCells('A29:L29')->setCellValue('A29', '4M / 1E CHANGE ASSESSMENT');


                // === 4M Assessment
                $rowsEffects = [
                    'Effect on Man (By Production)',
                    'Effect on Machine/Tools',
                    'Effect on Method/Environment',
                    'Effect on Materials',
                    'Line QC Remarks',
                    'PMI Approval',
                ];
                $startRowsEffects = 30;
                foreach ($rowsEffects as $i => $label) {
                    $sheet->setCellValue("A" . $startRowsEffects, $label);
                    // $sheet->setCellValue("D" . ($start + $i), 'Assessed by:');
                    // $sheet->setCellValue("F" . ($start + $i), 'Checked by: Section Head');
                    $startRowsEffects+=4;
                }
                $rowsAssessedby = [
                    'Assessed by',
                    'Assessed by',
                    'Assessed by',
                    'Assessed by',
                    'Assessed by',
                    'Assessed by',
                ];
                $startRowsAssessedby= 30;
                foreach ($rowsAssessedby as $i => $label) {
                    $sheet->setCellValue("I" . $startRowsAssessedby, $label);
                    $startRowsAssessedby+=4;
                }
                $rowsCheckedby = [
                    'Checked by',
                    'Checked by',
                    'Checked by',
                    'Checked by',
                    'Checked by',
                    'Checked by',
                ];
                $startRowsCheckedby= 32;
                foreach ($rowsCheckedby as $i => $label) {
                    $sheet->setCellValue("I" . $startRowsCheckedby, $label);
                    $startRowsCheckedby+=4;
                }
                $rowsSectionHead = [
                    'Section Head',
                    'Section Head',
                    'Section Head',
                    'Section Head',
                    'Section Head',
                    'Section Head',
                ];
                $startRowsSectionHead= 33;
                foreach ($rowsSectionHead as $i => $label) {
                    $sheet->setCellValue("K" . $startRowsSectionHead, $label);
                    $startRowsSectionHead+=4;
                }

                // === Approval Section
                $sheet->setCellValue('A50', 'PMI Approval');
                $sheet->setCellValue('B52', 'QC Head');
                $sheet->setCellValue('E52', 'Operations Head');
                $sheet->setCellValue('H27', 'QAD Head');

                $sheet->setCellValue('A56', 'YEC Approval?');
                $sheet->setCellValue('C56', '☐ Need');
                $sheet->setCellValue('C58', '☐ No Need');
                $sheet->setCellValue('H55', 'Final Disposition:');
                $sheet->setCellValue('I57', '☐ Accept');
                $sheet->setCellValue('I58', '☐ Reject');
                $sheet->setCellValue('H59', 'REMARKS:');


                // === NOTE Column
                $sheet->mergeCells('A59:G59')->setCellValue('A59', '**Note: If  YEC approval is necessary, PMI shall implement 4M change after the receipt of  YECs  Process
                    Change Application approval sheet.
                    If no need YEC approval, PMI can implement the  4M change immediately with PMI heads approval
                ');
                // === Conditional Section
                $sheet->mergeCells('A62:G62')->setCellValue('A62', 'USE THIS PORTION IF DISPOSITION IS ACCEPTED WITH CONDITION');
                $sheet->mergeCells('A63:B64')->setCellValue('A63', 'Action/s Required');
                $sheet->mergeCells('C63:D64')->setCellValue('C63', 'Target Date');
                $sheet->mergeCells('E63:F64')->setCellValue('E63', 'In-Charge');
                $sheet->mergeCells('G63:H64')->setCellValue('G63', 'Result');
                $sheet->setCellValue('I68', 'QAD SIGNATURE');

                // =========================================== //

                // === Column Widths
                foreach (range('A', 'L') as $col) {
                    $sheet->getColumnDimension($col)->setWidth(9.45);
                }

                // === Apply Styles to all cells used
                $sheet->getStyle('A59:G59')->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'wrapText' => true,
                    ],
                ]);
                // === Apply Styles to all cells used
                // $sheet->getStyle('A1:G40')->applyFromArray([
                //     'borders' => [
                //         'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                //     ],
                //     'alignment' => [
                //         'vertical' => Alignment::VERTICAL_CENTER,
                //         'horizontal' => Alignment::HORIZONTAL_LEFT,
                //         'wrapText' => true,
                //     ],
                // ]);

                // === Bold for header
                $sheet->getStyle('A1:A3')->getFont()->setBold(true);

                // Optional Row Heights
                for ($i = 1; $i <= 70; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(16.50);
                }
                for ($j = 59; $j <= 59; $j++) {
                    $sheet->getRowDimension($j)->setRowHeight( 33.5);
                }
            }
        ];
    }
}
