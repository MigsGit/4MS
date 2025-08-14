<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class EcrExport implements WithEvents, WithTitle, ShouldAutoSize, WithStrictNullComparison
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

    /**
     * @return string
     */
    public function title(): string
    {
        return 'ECR Data';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function(AfterSheet $event) {
                $requestedByDeptCollection = $this->ecr['requestedByDeptCollection'];
                $ecrCollection = $this->ecr['ecrCollection'];
                $ecrApprovalsCollection = $ecrCollection->ecr_approvals;
                $ecrDetailsCollection = $ecrCollection->ecr_details;

            //      echo  json_encode(
            //     [
            //         'AAAAAAAAAAAAA' => $ecrCollection,
            //         'BBBBBBBBBBBBBBBB' => $ecrApprovalsCollection,
            //         'CCCCCCCCCCCCCCCCCCCC' => $ecrDetailsCollection,
            //         ]
            //     );
            //   exit;
                $sheet = $event->sheet->getDelegate();


                // === Header Title ===
                $sheet->mergeCells('A2:H2');
                $sheet->setCellValue('A2', 'ENGINEERING CHANGE REQUEST');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'Aold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(25);

                // === Section Headers Styling ===

                $sectionHeaders = [
                    'A3' => 'INFORMATION',
                    'G3' => 'ECR NO.:',
                    'A9' => 'DESCRIPTION OF CHANGE',
                    'A16' => 'REASON OF CHANGE',
                    'A25' => 'REQUESTED BY',
                    'A29' => 'REVIEWED BY / ENGG. SECTION HEAD',
                    'A40' => 'AGREED BY',
                ];

                foreach ($sectionHeaders as $cell => $value) {
                    $sheet->setCellValue($cell, $value);
                    $sheet->getStyle($cell)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => '0000FF'], // Blue text for headers
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                }

                // === Section Information Content ===
                $sectionContents = [
                    'A4' => 'Customer Name:',
                    'A5' => 'Part Name:',
                    'A6' => 'Product Line:',
                    'A7' => 'Section:',
                    'A8' => 'Customer Name',

                    'F5' => 'Part Number:',
                    'F6' => 'Device Name:',
                    'F7' => 'Customer EC No. (If any):',
                    'F8' => 'Date of Request:',
                ];

                foreach ($sectionContents as $cell => $value) {
                    $sheet->setCellValue($cell, $value);
                    $sheet->getStyle($cell)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                }
                // === Approvers By Content ===
                $approverContents = [
                    'A26' => 'Department',
                    'B26' => 'Name',
                    'D26' => 'Title',
                    'E26' => 'Customer Name',
                    'F26' => 'Signature',
                    'H26' => 'Date',
                    // === Reviewed By / Section Head Content ===
                    'C30' => 'APPROVED',
                    'F30' => 'NOT APPROVED',
                    'A36' => 'Department',
                    'B36' => 'Name',
                    'D36' => 'Title',
                    'E36' => 'Customer Name',
                    'F36' => 'Signature',
                    'H36' => 'Date',
                    // === QA Content ===
                    'A41' => 'Department',
                    'B41' => 'Name',
                    'D41' => 'Title',
                    'E41' => 'Customer Name',
                    'F41' => 'Signature',
                    'H41' => 'Date',

                ];

                foreach ($approverContents as $cell => $value) {
                    $sheet->setCellValue($cell, $value);
                    $sheet->getStyle($cell)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                }
                //Ecr Collection Exist
                if(filled($ecrCollection)) {
                    $ecrCollectionContent = [
                        'B4' => $ecrCollection->customer_name,
                        'B5' => $ecrCollection->part_name,
                        'B6' => $ecrCollection->product_line,
                        'B7' => $ecrCollection->section,
                        'B8' => $ecrCollection->customer_name,

                        'G5' => $ecrCollection->part_no,
                        'G6' =>  $ecrCollection->device_name,
                        'G7' =>  $ecrCollection->customer_ec_no,
                        'G8' =>  $ecrCollection->date_of_request,
                    ];
                    foreach ($ecrCollectionContent as $cell => $value) {
                        $sheet->setCellValue($cell, $value);
                    }
                     //Ecr Collection Exist
                     /**
                        ecrApprovalsCollection
                        ecrDetailsCollection
                      */
                    if(filled($ecrDetailsCollection)) {
                        $startRowDocCollection = 10;
                        $startRowRocCollection = 17;
                        $startColumnEcrDetailsCollection = 'A';
                        foreach ($ecrDetailsCollection as $index => $value) {
                            $descriptionOfChange = $value->dropdown_master_detail_description_of_change->dropdown_masters_details;
                            $reasonOfChange = $value->dropdown_master_detail_reason_of_change->dropdown_masters_details;
                            $sheet->setCellValue("{$startColumnEcrDetailsCollection}{$startRowDocCollection}", $descriptionOfChange);
                            $startRowDocCollection++;

                            $sheet->setCellValue("{$startColumnEcrDetailsCollection}{$startRowRocCollection}", $reasonOfChange);
                            $startRowRocCollection++;
                        }
                    }


                    // if(filled($ecrApprovalsCollection)) {
                    //     $startRowEcrApprovalsCollection = 37;
                    //     $startColumnEcrApprovalsCollection = 'A';
                    //     foreach ($ecrApprovalsCollection as $index => $value) {
                    //         $descriptionOfChange = $value->dropdown_master_detail_description_of_change->dropdown_masters_details;
                    //         $reasonOfChange = $value->dropdown_master_detail_reason_of_change->dropdown_masters_details;
                    //         $sheet->setCellValue("{$startColumnEcrApprovalsCollection}{$startRowEcrApprovalsCollection}", $descriptionOfChange);
                    //         $startRowEcrApprovalsCollection++;
                    //     }
                    // }

                }

                // === Specific Merged Cells ===
                $mergeCells = [
                    'A3:F3', 'G3:H3',
                    'A9:H9',
                    'A16:H16',
                    'A25:H25',
                    'A29:H29',
                    'A46:H46',
                ];

                foreach ($mergeCells as $range) {
                    $sheet->mergeCells($range);
                }

                // === Column Widths ===
                $columnWidths = [
                    'A' => 20,
                    'C' => 20,
                    'D' => 20,
                    'E' => 20,
                    'F' => 20,
                    'G' => 20,
                    'H' => 20,
                ];
                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }

                // ===Row Heights for form look ===
                $customRowHeights = [
                    4 => 20,
                    9 => 20,
                    16 => 20,
                    25 => 20,
                    29 => 20,
                    46 => 20,
                ];
                foreach ($customRowHeights as $row => $height) {
                    $sheet->getRowDimension($row)->setRowHeight($height);
                }

                // Detect last row & column automatically
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // === Apply full borders to all cells ===
                // $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                // ->applyFromArray([
                //     'borders' => [
                //         'allBorders' => [
                //             'borderStyle' => Border::BORDER_THIN,
                //             'color' => ['rgb' => '000000'],
                //         ],
                //     ],
                // ]);

                // === Alignment for input cells ===
                // $sheet->getStyle("A5:H{$highestRow}")->applyFromArray([
                //     'alignment' => [
                //         'horizontal' => Alignment::HORIZONTAL_LEFT,
                //         'vertical' => Alignment::VERTICAL_CENTER,
                //         'wrapText' => true,
                //     ],
                // ]);

                // === Alignment for input cells ===
                // $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
                //     // 'font' => ['bold' => true, 'size' => 14],
                //     'alignment' => ['horizontal' => 'center'],
                //     'fill' => [
                //         'fillType' => 'solid',
                //         'startColor' => ['argb' => Color::COLOR_WHITE], // White background
                //         'wrapText' => true,
                //     ],
                // ]);

                // === 8. Freeze Pane (keep title and headers visible) ===
                // $sheet->freezePane('A5');
            },
        ];
    }
}
