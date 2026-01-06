<?php

namespace App\Exports\Sheets;

use Carbon\Carbon;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class InternalCcmSheet implements WithEvents, WithTitle, ShouldAutoSize, WithStrictNullComparison
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

    public function insertEsignatureImageIntoSheet($imagePath, $coordinates, $width, $height, $sheet,$tempPathExt=null)
    {
        $imageEsigPath = '../RapidX_E-Signature/'.$imagePath;
        // Get the full storage path of the image
        $imageStoragePath = $imageEsigPath.'.png';
        // $defaultSignature = Storage::path($imagePath.'.png'); $imageEsigPath

        if( !file_exists($imageStoragePath) ){
            echo  'Signature not found: Please as the HR for the E-Signature then Please file a ticket to http://rapidx/iss_service_request/my_tickets';

            exit;
        }
        // Resize the image
        $image = Image::make($imageStoragePath)->resize($width, $height);
        $tempPath = storage_path("app/temp_resized_image_".$tempPathExt.".png");
        $image->save($tempPath);

        // Insert the image into the worksheet
        $drawing = new Drawing();
        $drawing->setName("Inserted Image");
        $drawing->setDescription("Inserted Image");
        $drawing->setPath($tempPath); // Path to the resized image
        $drawing->setCoordinates($coordinates); // Cell coordinates
        $drawing->setWorksheet($sheet); // Attach the image to the worksheet
    }
    public function getEcrApprovalStatus($approvalStatus){
        try {
             switch ($approvalStatus) {
                 case 'OTRB':
                     $approvalStatus = 'Requested by:';
                     break;
                 case 'OTTE':
                     $approvalStatus = 'Technical Engg:';
                     break;
                 case 'OTRVB':
                     $approvalStatus = 'Reviewed By:';
                     break;
                 case 'QACB':
                     $approvalStatus = 'QA Engineer';
                     break;
                 case 'QAIN':
                     $approvalStatus = 'QA Manager';
                     break;
                 case 'QAEX':
                     $approvalStatus = 'QMS Head';
                     break;
                 default:
                     $approvalStatus = '';
                     break;
             }
             return  $approvalStatus;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        date_default_timezone_set('Asia/Manila');
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $requestedByDeptCollection = $this->ecr['requestedByDeptCollection'];
                $ecrCollection = $this->ecr['ecrCollection'];
                $ecrApprovalsCollection = $ecrCollection->ecr_approvals;
                $ecrDetailsCollection = $ecrCollection->ecr_details;
                $sheet = $event->sheet->getDelegate();

                // === Alignment for input cells ===
                $sheet->getStyle("A1:AA100")->applyFromArray([
                    // 'font' => ['bold' => true, 'size' => 12,'name'=> 'Arial'],
                    'font' => ['size' => 12,'name'=> 'Arial'],
                    'alignment' => ['horizontal' => 'center'],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['argb' => Color::COLOR_WHITE], // White background
                        'wrapText' => true,
                    ],
                ]);
                // === Header Title ===
                $sheet->mergeCells('A2:H2');
                $sheet->setCellValue('A2', 'ENGINEERING CHANGE REQUEST');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 20,
                        'name' => 'Arial',
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'D3D3D3' ], // White background
                        'wrapText' => true,
                    ],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(25);

                // === Section Headers Styling ===
                $sectionHeaders = [
                    'A3' => '1 INFORMATION',
                    'F3' => 'ECR NO.:'.' '. $ecrCollection->ecr_no,
                    'A9' => '2. 4M CATEGORY',
                    'A21' => '3. DESCRIPTION OF CHANGE',
                    'A33' => '4. REASON OF CHANGE',
                    'A42' => '5. REQUESTED BY',
                    'A46' => '6. TECHNICAL EVALUATION / ENGINEERING',
                    'A56' => '7. DOCUMENT REVISION',
                    'A62' => '8. AGREED BY',
                    'A67' => '9. PMI APPROVAL',
                    'A73' => '10. CUSTOMER APPROVAL',
                    'A80' => '11.  FINAL DISPOSITION',
                ];
                $sectionHeadersEndRow = [
                    '3',
                    '3',
                    '9',
                    '21',
                    '33',
                    '42',
                    '46',
                    '56',
                    '62',
                    '67',
                    '73',
                    '80',
                ];
                $sectionHeadersCount = 0;
                foreach ($sectionHeaders as $cell => $value) {
                    $sheet->setCellValue($cell, $value);
                    $sheet->getStyle("{$cell}:I".$sectionHeadersEndRow[$sectionHeadersCount])->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => '0000FF'], // Blue text for headers
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'fill' => [
                            'fillType' => 'solid',
                            'startColor' => ['rgb' => 'D3D3D3' ], // White background
                            'wrapText' => true,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => '000000'],
                            ],
                        ],
                    ]);
                    $sectionHeadersCount++;
                }

                // === 4M CATEGORY SECTION
                $categoryCol = "A";
                $categoryRow = "11";
                $isCategory = $ecrCollection->category ?? "";
                $category = [
                    $isCategory === "Man" ? '☑ Man' :'☐ Man',
                    $isCategory === "Machine" ? '☑ Machine/Tools' :'☐ Machine/Tools',
                    $isCategory === "Material" ? '☑ Material' :'☐ Material',
                    $isCategory === "Method" ? '☑ Method' :'☐ Method',
                    $isCategory === "Environment" ? '☑ Environment' :'☐ Environment',
                ];
                for ($i=0; $i < count($category); $i++) {
                    $sheet->setCellValue($categoryCol. $categoryRow, $category
                    [$i]);
                    $sheet->getStyle("".$categoryCol.$categoryRow."")->applyFromArray([

                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                    $categoryRow+=2;
                }
                // exit;
                // === 4M CATEGORY DETAILS SECTION
                $categoryDetailsCol = "B";
                $categoryDetailsRow = "12";
                $categoryDetails= "Kindly refer to PMI Change Control Procedure (PPS-I01-018) for 4M change factor categories.";
                for ($i=0; $i < count($category); $i++) {
                    $sheet->setCellValue($categoryDetailsCol. $categoryDetailsRow, $categoryDetails);
                    $sheet->getStyle("".$categoryDetailsCol.$categoryDetailsRow."")->applyFromArray([
                        'font' => [
                            'italic' => true,
                            'size' => 8,
                            'name' => 'Arial',
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],

                    ]);
                    $categoryDetailsRow+=2;

                }
                // === Section Information Content ===
                $sectionContents = [
                    'A4' => 'Customer Name:',
                    'A5' => 'Part Name:',
                    'A6' => 'Product Line:',
                    'A7' => 'Department:',
                    'A8' => 'Section:',
                    'F4' => 'Internal/External:',
                    'F5' => 'Part Number:',
                    'F6' => 'Device Name:',
                    'F7' => '4M Change Number:',
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
                    // === 5.  REQUESTED BY ===
                    'A43' => 'Department',
                    'C43' => 'Name',
                    'E43' => 'Title',
                    'F43' => 'Signature',
                    'H43' => 'Date',
                    'I43' => 'Remarks',
                    // === 6.  TECHNICAL EVALUATION / ENGINEERING ===
                    'D47' => 'APPROVED',
                    'H47' => 'NOT APPROVED',
                    'A51' => 'Department',
                    'C51' => 'Name',
                    'E51' => 'Title',
                    'F51' => 'Signature',
                    'H51' => 'Date',
                    'I51' => 'Remarks',
                    // === 7.  Document Revision ==
                    'A57' => 'Document Number',
                    'E57' => 'Rev. #',
                    'F57' => 'Person In-Charge',
                    'I57' => 'Revision Due Date',
                    // === 8.  AGREED BY ===
                    'A63' => 'Department',
                    'C63' => 'Name',
                    'E63' => 'Title',
                    'F63' => 'Signature',
                    'H63' => 'Date',
                    'I63' => 'Remarks',
                    // === 9.  PMI APPROVAL ===
                    'B71' => 'Prepared by:',
                    'E71' => 'Checked by: ',
                    'H71' => 'Approved by:',
                    // === 10. CUSTOMER APPROVAL ===
                    'D75' => 'NEED',
                    'H75' => 'NO NEED',
                    'A78' => 'Prepared by:',
                    'H78' => 'Checked by: ',
                    // === 11.  FINAL DISPOSITION ===
                    'D82' => 'ACCEPT',
                    'H82' => 'REJECT',

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

                $sheet->getStyle("C47")->applyFromArray([
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => '000000' ], // White background
                        'wrapText' => true,
                    ],
                ]);
                //Ecr Collection Exist
                if(filled($ecrCollection)) {
                    $ecrCollectionContent = [
                        'B4' => $ecrCollection->customer_name,
                        'B5' => $ecrCollection->part_name,
                        'B6' => $ecrCollection->product_line,
                        'B7' => $ecrCollection->section,
                        'B8' => $ecrCollection->customer_name,

                        'G4' => $ecrCollection->internal_external,
                        'G5' => $ecrCollection->part_no,
                        'G6' =>  $ecrCollection->device_name,
                        'G7' =>  $ecrCollection->ecr_no,
                        'G8' =>  $ecrCollection->date_of_request,
                    ];
                    foreach ($ecrCollectionContent as $cell => $value) {
                        $sheet->setCellValue($cell, $value);
                    }
                     //Ecr Collection Exist

                    if(filled($ecrDetailsCollection)) {
                        $startRowDocCollection = 23;
                        $startRowRocCollection = 34;
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


                    if(filled($ecrApprovalsCollection)) { //nmodify
                        $startRowRequestedByApprovalsCollection = 44;
                        $startRowOtherApprovalsCollection = 52;
                        $startRowQaApprovalCollection = 64;
                        // $startColumnOtherApprovalsCollection = 'A';
                        foreach ($ecrApprovalsCollection as $index => $value) {
                            $approvalStatus = $value->approval_status ?? "";
                            $ecrApprover = $value->rapidx_user->name ?? "";
                            // date('Y-m-d',$value->rapidx_user->created_at) ?? "";
                            $approvedDate = Carbon::parse($value->created_at)->format('m-d-Y') ?? "";
                            $division = $requestedByDeptCollection[$index]['division'] ?? "";
                            $filteredSection = $requestedByDeptCollection[$index]['filteredSection'] ?? "";
                            $remarks = $requestedByDeptCollection[$index]['remarks'] ?? "N/A";
                            if (str_contains($approvalStatus, 'QA')) {
                                $sheet->setCellValue("A{$startRowQaApprovalCollection}", $division);
                                $sheet->setCellValue("B{$startRowQaApprovalCollection}", $ecrApprover);
                                $sheet->setCellValue("D{$startRowQaApprovalCollection}",$this->getEcrApprovalStatus($approvalStatus));
                                // $sheet->setCellValue("E{$startRowQaApprovalCollection}", 'Signature');
                                $sheet->setCellValue("F{$startRowQaApprovalCollection}", $approvedDate);
                                $sheet->setCellValue("G{$startRowQaApprovalCollection}", $remarks);
                                  // === E-signature Images
                                // $imageEsigPath = 'public/e_signatures/';


                                $imageEsigWithEmpNumberPath = $value->rapidx_user->employee_number;
                                $this->insertEsignatureImageIntoSheet(
                                    $imageEsigWithEmpNumberPath,
                                    "E".$startRowQaApprovalCollection,
                                    50,
                                    50,
                                    $sheet,
                                    'ecr_qa'.$index
                                );
                                $startRowQaApprovalCollection++;
                            }else{
                                if (str_contains($approvalStatus, 'OTRB')) {
                                    $sheet->setCellValue("A{$startRowRequestedByApprovalsCollection}", $division);
                                    $sheet->setCellValue("B{$startRowRequestedByApprovalsCollection}", $ecrApprover);
                                    $sheet->setCellValue("D{$startRowRequestedByApprovalsCollection}",$this->getEcrApprovalStatus($approvalStatus));
                                    $sheet->setCellValue("F{$startRowRequestedByApprovalsCollection}", $approvedDate);
                                    $sheet->setCellValue("G{$startRowOtherApprovalsCollection}", $remarks);
                                    // === Insert e-signature
                                    // $imageEsigPath = 'public/e_signatures/';

                                    $imageEsigWithEmpNumberPath = $value->rapidx_user->employee_number;
                                    $this->insertEsignatureImageIntoSheet(
                                        $imageEsigWithEmpNumberPath,
                                        "E".$startRowRequestedByApprovalsCollection,
                                        50,
                                        50,
                                        $sheet,
                                        'ecr_requestedby'.$index
                                    );
                                    $startRowRequestedByApprovalsCollection++;
                                }
                                if ( !str_contains($approvalStatus, 'OTRB')) {
                                    $sheet->setCellValue("A{$startRowOtherApprovalsCollection}", $division);
                                    $sheet->setCellValue("B{$startRowOtherApprovalsCollection}", $ecrApprover);
                                    $sheet->setCellValue("D{$startRowOtherApprovalsCollection}", $approvalStatus);
                                    $sheet->setCellValue("D{$startRowOtherApprovalsCollection}",$this->getEcrApprovalStatus($approvalStatus));
                                    // $sheet->setCellValue("E{$startRowOtherApprovalsCollection}", 'Signature');
                                    $sheet->setCellValue("F{$startRowOtherApprovalsCollection}", $approvedDate);
                                    $sheet->setCellValue("G{$startRowOtherApprovalsCollection}", $remarks);
                                      // === Insert e-signature
                                    //   $imageEsigPath = 'public/e_signatures/';

                                      $imageEsigWithEmpNumberPath = $value->rapidx_user->employee_number;
                                      $this->insertEsignatureImageIntoSheet(
                                          $imageEsigWithEmpNumberPath,
                                          "E".$startRowOtherApprovalsCollection,
                                          50,
                                          50,
                                          $sheet,
                                          'ecr_engg'.$index
                                      );
                                    $startRowOtherApprovalsCollection++;
                                }
                            }
                        }
                    }
                }

                // === Specific Merged Cells ===
                $mergeCells = [
                    'A3:E3',
                    'F3:I3',
                    'A9:I9',
                    // INFORMATION
                    'G4:H4',
                    // HEADER
                    'A21:I21',
                    'A33:I33',
                    'A42:I42',
                    'A46:I46',
                    'A56:I56',
                    'A62:I62',
                    'A67:I67',
                    'A73:I73',
                    'A80:I80',
                    // 5.  REQUESTED BY
                    'A43:B43',
                    'C43:D43',
                    'F43:G43',
                    'I43:I43',
                    // 6.  TECHNICAL EVALUATION / ENGINEERING
                    'A51:B51',
                    'C51:D51',
                    'F51:G51',
                    'I51:I51',
                    // 7.  Document Revision
                    'A57:D57',
                    'F57:H57',
                    'I57:I57',
                    // 8.  AGREED BY
                    'A63:B63',
                    'C63:D63',
                    'F63:G63',
                    // 8.  AGREED BY
                    'A77:B77',
                    'H77:I77',
                ];

                foreach ($mergeCells as $range) {
                    $sheet->mergeCells($range);
                }


                // ===Row Heights for form look ===
                $customRowHeights = [
                    4 => 20,
                    9 => 20,
                    16 => 20,
                    25 => 20,
                    29 => 20,
                    40 => 20,
                ];
                foreach ($customRowHeights as $row => $height) {
                    $sheet->getRowDimension($row)->setRowHeight($height);
                }


                // Set border for range
                // echo 'true';
                // exit;
                // === Apply borders to specific cells ===
                $allThinBorder = [
                    "A43:I43",
                    "C47",
                    "G47",
                    "A51:I51",
                    'A57:I57',
                    'A63:I63',
                    'C75',
                    'G75',
                    'C82',
                    'G82',
                ];
                foreach ($allThinBorder as $key => $allThinBorderValue) {
                    $sheet->getStyle($allThinBorderValue)
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }
                // === THIN BORDERS
                $rightThinBorder = [
                    "A5:A8",
                    "E5:E8",
                    "F5:F8",
                ];


                $bottomThinBorder = [
                    "A4:I4",
                    "B70",
                    // 9. PMI APPROVAL
                    "E70",
                    "H70",
                    //10. CUSTOMER APPROVAL
                    "A77:B77",
                    "H77:I77",
                ];

                foreach ($rightThinBorder as $key => $rightThinBorderValue) {
                    $sheet->getStyle($rightThinBorderValue)
                    ->applyFromArray([
                        'borders' => [
                            'right' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }
                foreach ($bottomThinBorder as $key => $bottomThinBorderValue) {
                    $sheet->getStyle($bottomThinBorderValue)
                    ->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }

                //THICK BORDERS
                $rightThickBorder = [
                    "I2:I84",
                ];
                $leftThickBorder = [
                    "A2:I84",
                ];
                $topThickBorder = [
                    "A2:I2",
                    "A85:I85",
                ];
                foreach ($rightThickBorder as $key => $rightThickBorderValue) {
                    $sheet->getStyle($rightThickBorderValue)
                    ->applyFromArray([
                        'borders' => [
                            'right' => [
                                'borderStyle' => Border::BORDER_THICK,
                            ],
                        ],
                    ]);
                }
                foreach ($leftThickBorder as $key => $leftThickBorderValue) {
                    $sheet->getStyle($leftThickBorderValue)
                    ->applyFromArray([
                        'borders' => [
                            'left' => [
                                'borderStyle' => Border::BORDER_THICK,
                            ],
                        ],
                    ]);
                }
                foreach ($topThickBorder as $key => $topThickBorderValue) {
                    $sheet->getStyle($topThickBorderValue)
                    ->applyFromArray([
                        'borders' => [
                            'top' => [
                                'borderStyle' => Border::BORDER_THICK,
                            ],
                        ],
                    ]);
                }

                $sheet->getStyle("B5:B8")->applyFromArray([
                    'alignment' => ['horizontal' => 'left'],
                ]);

                $sheet->getStyle("G5:G8")->applyFromArray([
                    'alignment' => ['horizontal' => 'left'],
                ]);

                // === Column Widths ===
                $columnWidths = [
                    'A' => -100,
                    'B' => 35,
                    'C' => 6,
                    'D' => 35,
                    'E' => 35,
                    'F' => 35,
                    'G' => 6,
                    'H' => 35,
                    'I' => 35,
                    'J' => 20,
                ];
                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }


            },
        ];
    }
}
