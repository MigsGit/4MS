<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
                $sheet = $event->sheet->getDelegate();

                // Detect last row & column automatically
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();

                // === 1. Apply full borders to all cells ===
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'],
                            ],
                        ],
                    ]);

                // === 2. Main Title ===
                $sheet->mergeCells('B2:H2');
                $sheet->setCellValue('B2', 'ENGINEERING CHANGE REQUEST');
                $sheet->getStyle('B2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(25);

                // === 3. Section Headers Styling ===

                $sectionHeaders = [
                    'B3' => 'INFORMATION',
                    'G3' => 'ECR NO.:',
                    'B9' => 'DESCRIPTION OF CHANGE',
                    'B16' => 'REASON OF CHANGE',
                    'B25' => 'REQUESTED BY',
                    'B29' => 'REVIEWED BY / ENGINEERING',
                    'B46' => 'AGREED BY',
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

                $sectionContents = [
                    'B4' => 'Customer Name:',
                    'B5' => 'Part Name:',
                    'B6' => 'Product Line:',
                    'B7' => 'Section:',
                    'B8' => 'Customer Name',

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

                // === 4. Specific Merged Cells ===
                $mergeCells = [
                    'B3:F3', 'G3:H3',
                    'B9:H9',
                    'B16:H16',
                    'B25:H25',
                    'B29:H29',
                    'B46:H46',
                ];
                foreach ($mergeCells as $range) {
                    $sheet->mergeCells($range);
                }

                // === 5. Column Widths ===
                $columnWidths = [
                    'A' => 2,  // Padding column
                    'B' => 20,
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

                // === 6. Row Heights for form look ===
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

                // === 7. Alignment for input cells ===
                $sheet->getStyle("B5:H{$highestRow}")->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);

                // === 8. Freeze Pane (keep title and headers visible) ===
                // $sheet->freezePane('B5');
            },
        ];
    }
}
