<?php

namespace App\Exports\Sheets;

use Carbon\Carbon;
use App\Interfaces\CommonInterface;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
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
    // protected $commonInterface;

    /**
     * @return \Illuminate\Support\Collection
     */
    // public function __construct($ecr,CommonInterface $commonInterface) {
    public function __construct($ecr) {
        $this->ecr = $ecr;
        // $this->commonInterface = $commonInterface;
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
            echo  ''.$imageStoragePath.'Signature not found: Please as the HR for the E-Signature then Please file a ticket to http://rapidx/iss_service_request/my_tickets';

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

                // $detailsFourM = $this->ecr['detailsFourM'];
                $detailsFourMCollection = $this->ecr['detailsFourMCollectionFiltered'];
                $detailsFourMApprovalByDeptCollection = $this->ecr['detailsFourMApprovalByDeptCollection'];
                $pmiApprovalCollection = collect($ecrCollection['pmi_approvals'])->groupBy('approval_status')->toArray();
                $isImageRefExist = $ecrCollection->category === "Method" || $ecrCollection->category === "Machine";
                if( $isImageRefExist) {
                    $beforeAfterFileStorage = $this->ecr['beforeAfterFileStorage'][0];
                }

                $ecrApprovalsCollection = $ecrCollection->ecr_approvals;
                $ecrDetailsCollection = $ecrCollection->ecr_details;
                $documentDetails = $ecrCollection->document_details;

                $sheet = $event->sheet->getDelegate();

                // === Alignment for input cells ===
                $sheet->getStyle("A1:AA200")->applyFromArray([
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
                $sheet->mergeCells('A2:I2');
                $sheet->setCellValue('A2', '4M CHANGE CONTROL MANAGEMENT');
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
                    // 'A67' => '9. PMI APPROVAL',

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

                 // ======= Insert Before and After Image ========
                // Retrieve the image path
                if( $isImageRefExist) {
                    $filteredDocumentNameBefore = explode(' | ',$beforeAfterFileStorage->filtered_document_name_before);
                    // echo json_encode($filteredDocumentNameBefore);
                    // exit;
                    $storageImageDirBefore= Storage::path('public/'.strtolower($ecrCollection->category).'/'.$ecrCollection->id.'/before/');
                    if(file_exists($storageImageDirBefore) ){

                        $startBeforeImageCol = "A";
                        $startBeforeImageRow = "24";
                        foreach ($filteredDocumentNameBefore as $key => $valueBefore) {
                            $imagePathBefore[]= $storageImageDirBefore.$valueBefore;
                        }

                        foreach ($imagePathBefore as $key => $imagePathBeforeValue) {
                                // Resize the image (optional, requires Intervention Image package)
                                $image = Image::make($imagePathBeforeValue)->resize(600,600); // Resize to 300x300 pixels
                                $tempPath = storage_path("app/temp_resized_image_$key.jpg");
                                $image->save($tempPath);

                                // Calculate the cell coordinates dynamically
                                $currentRow = $startBeforeImageRow + ($key*1); // Move down 5 rows for each image

                                // Merge cells to accommodate the image
                                $endColumn = chr(ord($startBeforeImageCol) + 2); // Merge 3 columns (e.g., A, B, C)

                                // Dynamically adjust column widths and row heights
                                $imageWidth = $image->width();
                                $imageHeight = $image->height();

                                $columnWidth = $imageWidth / 9.5; // Approximation for column width

                                $rowHeight = $imageHeight / 1.5; // Approximation for row height
                                $sheet->getRowDimension($currentRow)->setRowHeight($rowHeight);
                                $sheet->getRowDimension($currentRow + 1)->setRowHeight($rowHeight);

                                // Insert the image into the merged cells
                                $drawing = new Drawing();

                                $drawing->setName("Image $key");

                                $drawing->setDescription("Image $key");
                                $drawing->setPath($tempPath); // Path to the resized image

                                $drawing->setCoordinates("$startBeforeImageCol$currentRow"); // Place the image at the top-left of the merged cells

                                $drawing->setWorksheet($sheet); // Attach the image to the worksheet

                        }

                    }

                    $filteredDocumentNameAfter = explode(' | ',$beforeAfterFileStorage->filtered_document_name_after);
                    $storageImageDirAfter= Storage::path('public/'.strtolower($ecrCollection->category).'/'.$ecrCollection->id.'/after/');
                    if(file_exists($storageImageDirBefore) ){
                        $startAfterImageCol = "E";
                        $startAfterImageRow = "24";
                        foreach ($filteredDocumentNameAfter as $index => $valueAfter) {
                            $imagePathAfter[]= $storageImageDirAfter.$valueAfter;
                        }

                        foreach ($imagePathAfter as $index => $imagePathAfterValue) {
                                // Resize the image (optional, requires Intervention Image package)
                                $image = Image::make($imagePathAfterValue)->resize(600,600); // Resize to 300x300 pixels
                                $tempPath = storage_path("app/temp_resized_image_after_$index.jpg");
                                $image->save($tempPath);

                                // Calculate the cell coordinates dynamically
                                $currentRow = $startAfterImageRow + ($index*1); // Move down 5 rows for each image

                                // Merge cells to accommodate the image
                                $endColumn = chr(ord($startAfterImageCol) + 2); // Merge 3 columns (e.g., A, B, C)

                                // Dynamically adjust column widths and row heights
                                $imageWidth = $image->width();
                                $imageHeight = $image->height();

                                $columnWidth = $imageWidth / 10.5; // Approximation for column width

                                $rowHeight = $imageHeight / 1.5; // Approximation for row height
                                $sheet->getRowDimension($currentRow)->setRowHeight($rowHeight);
                                $sheet->getRowDimension($currentRow + 1)->setRowHeight($rowHeight);

                                // Insert the image into the merged cells
                                $drawing = new Drawing();
                                $drawing->setName("Image $index");
                                $drawing->setDescription("Image $index");
                                $drawing->setPath($tempPath); // Path to the resized image
                                $drawing->setCoordinates("$startAfterImageCol$currentRow"); // Place the image at the top-left of the merged cells
                                $drawing->setWorksheet($sheet); // Attach the image to the worksheet

                        }

                    }
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
                        $sheet->getStyle($cell)->applyFromArray([
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_LEFT,
                                'vertical' => Alignment::VERTICAL_CENTER,
                            ],
                        ]);
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


                    if(filled($ecrApprovalsCollection)) {
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


                            if (str_contains($approvalStatus, 'OTRB')) {
                                $sheet->setCellValue("A{$startRowRequestedByApprovalsCollection}", $division);
                                $sheet->setCellValue("C{$startRowRequestedByApprovalsCollection}", $ecrApprover);
                                $sheet->setCellValue("E{$startRowRequestedByApprovalsCollection}",$this->getEcrApprovalStatus($approvalStatus));
                                $sheet->setCellValue("H{$startRowRequestedByApprovalsCollection}", $approvedDate);
                                $sheet->setCellValue("I{$startRowOtherApprovalsCollection}", $remarks);
                                // === Insert e-signature
                                // $imageEsigPath = 'public/e_signatures/';

                                $imageEsigWithEmpNumberPath = $value->rapidx_user->employee_number;
                                $this->insertEsignatureImageIntoSheet(
                                    $imageEsigWithEmpNumberPath,
                                    "F".$startRowRequestedByApprovalsCollection,
                                    50,
                                    50,
                                    $sheet,
                                    'ecr_requestedby'.$index
                                );
                                $sheet->getStyle("A{$startRowRequestedByApprovalsCollection}:I{$startRowRequestedByApprovalsCollection}")->applyFromArray([
                                    'alignment' => [
                                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                                        'vertical' => Alignment::VERTICAL_CENTER,
                                    ],
                                    'borders' => [
                                        'allBorders' => [
                                            'borderStyle' => Border::BORDER_THIN,
                                        ],
                                    ],
                                    'fill' => [
                                        // 'fillType' => 'solid',
                                        // 'startColor' => ['rgb' => 'D3D3D3' ], // White background
                                        'wrapText' => true,
                                    ],
                                ]);
                                $startRowRequestedByApprovalsCollection++;
                            }
                            if ( str_contains($approvalStatus, 'OTTE')) {
                                $sheet->setCellValue("A{$startRowOtherApprovalsCollection}", $division);
                                $sheet->setCellValue("C{$startRowOtherApprovalsCollection}", $ecrApprover);
                                $sheet->setCellValue("E{$startRowOtherApprovalsCollection}",$this->getEcrApprovalStatus($approvalStatus));
                                $sheet->setCellValue("H{$startRowOtherApprovalsCollection}", $approvedDate);
                                $sheet->setCellValue("I{$startRowOtherApprovalsCollection}", $remarks);
                                  // === Insert e-signature

                                  $imageEsigWithEmpNumberPath = $value->rapidx_user->employee_number;
                                  $this->insertEsignatureImageIntoSheet(
                                      $imageEsigWithEmpNumberPath,
                                      "F".$startRowOtherApprovalsCollection,
                                      50,
                                      50,
                                      $sheet,
                                      'ecr_engg'.$index
                                  );
                                $sheet->getStyle("A{$startRowOtherApprovalsCollection}:I{$startRowOtherApprovalsCollection}")->applyFromArray([
                                    'alignment' => [
                                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                                        'vertical' => Alignment::VERTICAL_CENTER,
                                    ],
                                    'borders' => [
                                        'allBorders' => [
                                            'borderStyle' => Border::BORDER_THIN,
                                        ],
                                    ],
                                    'fill' => [
                                        // 'fillType' => 'solid',
                                        // 'startColor' => ['rgb' => 'D3D3D3' ], // White background
                                        'wrapText' => true,
                                    ],
                                ]);
                                $startRowOtherApprovalsCollection++;
                            }
                        }

                    }
                    //                'detailsFourMCollection' => $detailsFourMCollection,

                    // echo json_encode($detailsFourMCollectionFiltered);
                    // exit;
                    if( count($detailsFourMCollection) != 0) {

                        foreach ($detailsFourMCollection as $index => $value) {

                           $division = $detailsFourMApprovalByDeptCollection[$index]['division'] ?? "";
                            $rapidxFullName = $detailsFourMCollection[$index]->rapidx_user->name ?? "";
                            $departmentId = $detailsFourMCollection[$index]->rapidx_user->department_id ?? "";
                            $approvedDate = Carbon::parse($detailsFourMCollection[$index]->updated_at)->format('m-d-Y') ?? "";
                            $approvalStatus = $detailsFourMCollection[$index]->approval_status ?? "";
                            $remarks = $detailsFourMCollection[$index]->remarks ?? "";

                            $sheet->setCellValue("A{$startRowQaApprovalCollection}", $division);
                            $sheet->setCellValue("C{$startRowQaApprovalCollection}", $rapidxFullName);
                            $sheet->setCellValue("E{$startRowQaApprovalCollection}",$this->getEcrApprovalStatus($approvalStatus));
                            $sheet->setCellValue("H{$startRowQaApprovalCollection}", $approvedDate);
                            $sheet->setCellValue("I{$startRowQaApprovalCollection}", $remarks);
                            // === Insert e-signature
                            $imageEsigWithEmpNumberPath = $detailsFourMCollection[$index]->rapidx_user->employee_number;
                            $this->insertEsignatureImageIntoSheet(
                                $imageEsigWithEmpNumberPath,
                                "F".$startRowQaApprovalCollection,
                                50,
                                50,
                                $sheet,
                                'agree_by'.$index
                            );
                            //Design
                            $sheet->getStyle("A{$startRowQaApprovalCollection}:I{$startRowQaApprovalCollection}")->applyFromArray([
                                'alignment' => [
                                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                                    'vertical' => Alignment::VERTICAL_CENTER,

                                ],
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => Border::BORDER_THIN,
                                    ],
                                ],
                            ]);

                            $sheet->mergeCells("A{$startRowQaApprovalCollection}:B{$startRowQaApprovalCollection}");
                            $sheet->mergeCells("C{$startRowQaApprovalCollection}:D{$startRowQaApprovalCollection}");
                            $sheet->mergeCells("F{$startRowQaApprovalCollection}:G{$startRowQaApprovalCollection}");
                            $event->sheet->getDelegate()->getStyle("A{$startRowQaApprovalCollection}:I{$startRowQaApprovalCollection}")->getAlignment()->setWrapText(true);
                            $sheet->getRowDimension($startRowQaApprovalCollection)->setRowHeight(105);
                            $startRowQaApprovalCollection++;
                        }
                    }else{
                        $sheet->setCellValue("A{$startRowQaApprovalCollection}", 'N/A');
                        $sheet->setCellValue("C{$startRowQaApprovalCollection}", 'N/A');
                        $sheet->setCellValue("E{$startRowQaApprovalCollection}", 'N/A');
                        $sheet->setCellValue("H{$startRowQaApprovalCollection}", 'N/A');
                        $sheet->setCellValue("I{$startRowQaApprovalCollection}", 'N/A');
                    }
                }

                // 5.  DOCUMENT REVISION
                if(filled($documentDetails)){
                    $startRowDocDetailsCollection = 58;
                    // echo ($documentDetails);
                    // exit;
                    $startColumnDocDetailsCollection = [
                        'A',
                        'E',
                        'F',
                        'I',
                    ];
                    foreach ($documentDetails as $key => $documentDetailsValue) {
                        $sheet->setCellValue("{$startColumnDocDetailsCollection[0]}{$startRowDocDetailsCollection}", $documentDetailsValue['document_number']);
                        $sheet->setCellValue("{$startColumnDocDetailsCollection[1]}{$startRowDocDetailsCollection}", $documentDetailsValue['revision_no']);
                        $sheet->setCellValue("{$startColumnDocDetailsCollection[2]}{$startRowDocDetailsCollection}", $documentDetailsValue['rapidx_user_person_in_charge']['name']);
                        $sheet->setCellValue("{$startColumnDocDetailsCollection[3]}{$startRowDocDetailsCollection}", $documentDetailsValue['revision_due_date']);

                        $sheet->getStyle("A{$startRowDocDetailsCollection}:I{$startRowDocDetailsCollection}")->applyFromArray([
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_LEFT,
                                'vertical' => Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        $startRowDocDetailsCollection++;
                    }
                }

                // === DYNAMIC ROW 8 - 10 SECTION HEADER
                $sectionHeaders = $startRowQaApprovalCollection;
                $sectionHeadersEndRow = [
                    '9. PMI APPROVAL',
                    '10. CUSTOMER APPROVAL',
                    '11.  FINAL DISPOSITION',
                ];

                foreach ($sectionHeadersEndRow as $cell => $value) {
                    $sheet->setCellValue("A$sectionHeaders", $value);
                    $sheet->mergeCells("A{$sectionHeaders}:I{$sectionHeaders}");

                    // echo json_encode($cell);
                    $sheet->getStyle("A{$sectionHeaders}:I{$sectionHeaders}")
                    ->applyFromArray([
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
                    $sectionHeaders += 7;
                }
                //=== Dynamic Approver Contents ===
                $pmiCustomerApprovalRow = $startRowQaApprovalCollection+4;
                $needNoNeedRow = $pmiCustomerApprovalRow+5;
                $customerApprovalRow = $needNoNeedRow+3;
                $finalDispoRow = $customerApprovalRow+6;
                $approverContentsDynamic = [
                    'A'.$pmiCustomerApprovalRow => 'Prepared by:',
                    'E'.$pmiCustomerApprovalRow => 'Checked by: ',
                    'H'.$pmiCustomerApprovalRow => 'Approved by:',
                    // === 10. CUSTOMER APPROVAL ===
                    'D'.$needNoNeedRow => 'NEED',
                    'H'.$needNoNeedRow => 'NO NEED',
                    'A'.$customerApprovalRow => 'Prepared by:',
                    'H'.$customerApprovalRow => 'Checked by: ',
                    // === 11.  FINAL DISPOSITION ===
                    'D'.$finalDispoRow => 'ACCEPT',
                    'H'.$finalDispoRow => 'REJECT',
                ];


                foreach ($approverContentsDynamic as $cell => $value) {
                    $sheet->setCellValue($cell, $value);
                    $sheet->getStyle($cell)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                }
                // ==== PMI APPROVAL ====
                $internalPbCol = "A";
                $internalPb = $pmiApprovalCollection['PB'] ?? null;
                if(filled($internalPb)){
                    $pmiCustomerApprovalRowRowStart = $pmiCustomerApprovalRow-2;
                    $pmiCustomerApprovalRowRowStartValue = $pmiCustomerApprovalRow-1;
                    foreach ($internalPb as $key => $internalPbValue) {
                        $this->insertEsignatureImageIntoSheet(
                            $internalPbValue['rapidx_user']['employee_number'],
                            $internalPbCol.$pmiCustomerApprovalRowRowStart,
                            50,
                            50,
                            $sheet,
                            'pb_head'.$key
                        );

                        $sheet->setCellValue($internalPbCol.$pmiCustomerApprovalRowRowStartValue, $internalPbValue['rapidx_user']['name']);
                        $pmiCustomerApprovalRowRowStart++; //Adjust the Column
                    }
                }

                $startInternalCbCol = "E";
                $internalCb = $pmiApprovalCollection['CB'] ?? null;
                if(filled($internalCb)){
                    $pmiCustomerApprovalRowRowStart = $pmiCustomerApprovalRow-2;
                    $pmiCustomerApprovalRowRowStartValue = $pmiCustomerApprovalRow-1;
                    foreach ($internalCb as $key => $internalCbValue) {
                        $this->insertEsignatureImageIntoSheet(
                            $internalCbValue['rapidx_user']['employee_number'],
                            $startInternalCbCol.$pmiCustomerApprovalRowRowStart,
                            50,
                            50,
                            $sheet,
                            'cb_head'.$key
                        );

                        $sheet->setCellValue($startInternalCbCol.$pmiCustomerApprovalRowRowStartValue, $internalCbValue['rapidx_user']['name']);
                        $pmiCustomerApprovalRowRowStart++; //Adjust the Column
                    }
                }

                $startInternalAbCol = "H";
                $internalAb = $pmiApprovalCollection['AB'] ?? null;
                if(filled($internalAb)){
                    foreach ($internalAb as $key => $internalAbValue) {
                        $pmiCustomerApprovalRowRowStart = $pmiCustomerApprovalRow-2;
                        $pmiCustomerApprovalRowRowStartValue = $pmiCustomerApprovalRow-1;
                        $this->insertEsignatureImageIntoSheet(
                            $internalAbValue['rapidx_user']['employee_number'],
                            $startInternalAbCol.$pmiCustomerApprovalRowRowStart,
                            50,
                            50,
                            $sheet,
                            'ab_head'.$key
                        );

                        $sheet->setCellValue($startInternalAbCol.$pmiCustomerApprovalRowRowStartValue, $internalAbValue['rapidx_user']['name']);
                        $pmiCustomerApprovalRowRowStart++; //Adjust the Column
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
                    // 5.  REQUESTED BY
                    'A43:B43',
                    'C43:D43',
                    'F43:G43',
                    'I43:I43',
                    // 5.  Document Revision
                    "A58:D58",
                    "A59:D59",
                    "A60:D60",
                    "A61:D61",
                    "F58:H58",
                    "F59:H59",
                    "F60:H60",
                    "F61:H61",
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
                $allThinBorderMergeRow = [
                    "44",
                    "45",

                    "52",
                    "53",
                    "54",
                    "55",
                ];

                foreach ($allThinBorderMergeRow as $key => $allThinBorderMergeRowValue) {
                    $sheet->mergeCells("A{$allThinBorderMergeRowValue}:B{$allThinBorderMergeRowValue}");
                    $sheet->mergeCells("C{$allThinBorderMergeRowValue}:D{$allThinBorderMergeRowValue}");
                    $sheet->mergeCells("F{$allThinBorderMergeRowValue}:G{$allThinBorderMergeRowValue}");

                    $sheet->getStyle("A{$allThinBorderMergeRowValue}:I{$allThinBorderMergeRowValue}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }


                // === Apply borders to specific cells ===
                $allThinBorder = [
                    "A43:I43",
                    "C47",
                    "G47",
                    // 6, 10 , 11 SECTION
                    "A51:I51",
                    'A57:I57',
                    'A63:I63',
                    'C'.$needNoNeedRow, //nmodify
                    'G'.$needNoNeedRow,
                    'C'.$finalDispoRow,
                    'G'.$finalDispoRow,
                    //=== 7. DOCUMENT REVISION
                    "A58:I61",
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
                    "A4:A8",
                    "E4:E8",
                    "F4:F8",

                ];


                $bottomThinBorder = [
                    // "A4:I4",
                    // // 9. PMI APPROVAL
                    // "A70",
                    // "E70",
                    // "H70",
                    // //10. CUSTOMER APPROVAL
                    // "A77:B77",
                    // "H77:I77",
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
                $finalDispoRowEnd = $finalDispoRow+2;

                $rightThickBorder = [
                    "A2:I{$finalDispoRowEnd}", //DYNAMIC TOP THICK BORDER
                ];
                $leftThickBorder = [
                    "A2:I{$finalDispoRowEnd}", //DYNAMIC TOP THICK BORDER
                ];

                $topThickBorder = [
                    "A2:I2",
                ];
                $bottomThickBorder = [
                    "A{$finalDispoRowEnd}:I{$finalDispoRowEnd}", //DYNAMIC TOP THICK BORDER
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
                foreach ($bottomThickBorder as $key => $bottomThickBorderValue) {
                    $sheet->getStyle($bottomThickBorderValue)
                    ->applyFromArray([
                        'borders' => [
                            'bottom' => [
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
                    'B' => 13,
                    'C' => 6,
                    'D' => 35,
                    'E' => 35,
                    'F' => 35,
                    'G' => 6,
                    'H' => 30,
                    'I' => 30,
                    'J' => 20,
                ];
                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }


            },
        ];
    }
    public function registerEventsOrig(): array
    {
        date_default_timezone_set('Asia/Manila');
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $requestedByDeptCollection = $this->ecr['requestedByDeptCollection'];
                $ecrCollection = $this->ecr['ecrCollection'];
                $pmiApprovalCollection = collect($ecrCollection['pmi_approvals'])->groupBy('approval_status')->toArray();
                // echo json_encode($ecrCollection);
                // exit ;
                $beforeAfterFileStorage = $this->ecr['beforeAfterFileStorage'][0];

                $ecrApprovalsCollection = $ecrCollection->ecr_approvals;
                $ecrDetailsCollection = $ecrCollection->ecr_details;
                $documentDetails = $ecrCollection->document_details;
                $sheet = $event->sheet->getDelegate();

                // === Alignment for input cells ===
                $sheet->getStyle("A1:AA200")->applyFromArray([
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
                $sheet->mergeCells('A2:I2');
                $sheet->setCellValue('A2', '4M CHANGE CONTROL MANAGEMENT');
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

                 // ======= Insert Before and After Image ========
                // Retrieve the image path
                $filteredDocumentNameBefore = explode(' | ',$beforeAfterFileStorage->filtered_document_name_before);
                $storageImageDirBefore= Storage::path('public/'.strtolower($ecrCollection->category).'/'.$ecrCollection->id.'/before/');
                if(file_exists($storageImageDirBefore) ){

                    $startBeforeImageCol = "A";
                    $startBeforeImageRow = "24";
                    foreach ($filteredDocumentNameBefore as $key => $valueBefore) {
                        $imagePathBefore[]= $storageImageDirBefore.$valueBefore;
                    }

                    foreach ($imagePathBefore as $key => $imagePathBeforeValue) {
                            // Resize the image (optional, requires Intervention Image package)
                            $image = Image::make($imagePathBeforeValue)->resize(600,600); // Resize to 300x300 pixels
                            $tempPath = storage_path("app/temp_resized_image_$key.jpg");
                            $image->save($tempPath);

                            // Calculate the cell coordinates dynamically
                            $currentRow = $startBeforeImageRow + ($key*1); // Move down 5 rows for each image

                            // Merge cells to accommodate the image
                            $endColumn = chr(ord($startBeforeImageCol) + 2); // Merge 3 columns (e.g., A, B, C)

                            // Dynamically adjust column widths and row heights
                            $imageWidth = $image->width();
                            $imageHeight = $image->height();

                            $columnWidth = $imageWidth / 9.5; // Approximation for column width

                            $rowHeight = $imageHeight / 1.5; // Approximation for row height
                            $sheet->getRowDimension($currentRow)->setRowHeight($rowHeight);
                            $sheet->getRowDimension($currentRow + 1)->setRowHeight($rowHeight);

                            // Insert the image into the merged cells
                            $drawing = new Drawing();

                            $drawing->setName("Image $key");

                            $drawing->setDescription("Image $key");
                            $drawing->setPath($tempPath); // Path to the resized image

                            $drawing->setCoordinates("$startBeforeImageCol$currentRow"); // Place the image at the top-left of the merged cells

                            $drawing->setWorksheet($sheet); // Attach the image to the worksheet

                    }

                }

                $filteredDocumentNameAfter = explode(' | ',$beforeAfterFileStorage->filtered_document_name_after);
                $storageImageDirAfter= Storage::path('public/'.strtolower($ecrCollection->category).'/'.$ecrCollection->id.'/after/');
                if(file_exists($storageImageDirBefore) ){
                    $startAfterImageCol = "E";
                    $startAfterImageRow = "24";
                    foreach ($filteredDocumentNameAfter as $index => $valueAfter) {
                        $imagePathAfter[]= $storageImageDirAfter.$valueAfter;
                    }

                    foreach ($imagePathAfter as $index => $imagePathAfterValue) {
                            // Resize the image (optional, requires Intervention Image package)
                            $image = Image::make($imagePathAfterValue)->resize(600,600); // Resize to 300x300 pixels
                            $tempPath = storage_path("app/temp_resized_image_after_$index.jpg");
                            $image->save($tempPath);

                            // Calculate the cell coordinates dynamically
                            $currentRow = $startAfterImageRow + ($index*1); // Move down 5 rows for each image

                            // Merge cells to accommodate the image
                            $endColumn = chr(ord($startAfterImageCol) + 2); // Merge 3 columns (e.g., A, B, C)

                            // Dynamically adjust column widths and row heights
                            $imageWidth = $image->width();
                            $imageHeight = $image->height();

                            $columnWidth = $imageWidth / 10.5; // Approximation for column width

                            $rowHeight = $imageHeight / 1.5; // Approximation for row height
                            $sheet->getRowDimension($currentRow)->setRowHeight($rowHeight);
                            $sheet->getRowDimension($currentRow + 1)->setRowHeight($rowHeight);

                            // Insert the image into the merged cells
                            $drawing = new Drawing();
                            $drawing->setName("Image $index");
                            $drawing->setDescription("Image $index");
                            $drawing->setPath($tempPath); // Path to the resized image
                            $drawing->setCoordinates("$startAfterImageCol$currentRow"); // Place the image at the top-left of the merged cells
                            $drawing->setWorksheet($sheet); // Attach the image to the worksheet

                    }

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
                    'A71' => 'Prepared by:',
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
                        $sheet->getStyle($cell)->applyFromArray([
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_LEFT,
                                'vertical' => Alignment::VERTICAL_CENTER,
                            ],
                        ]);
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
                    if(filled($ecrApprovalsCollection)) {
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
                            if (str_contains($approvalStatus,'QA')) {

                                $sheet->setCellValue("A{$startRowQaApprovalCollection}", $division);
                                $sheet->setCellValue("C{$startRowQaApprovalCollection}", $ecrApprover);
                                $sheet->setCellValue("E{$startRowQaApprovalCollection}",$this->getEcrApprovalStatus($approvalStatus));
                                // $sheet->setCellValue("E{$startRowQaApprovalCollection}", 'Signature');
                                $sheet->setCellValue("H{$startRowQaApprovalCollection}", $approvedDate);
                                $sheet->setCellValue("I{$startRowQaApprovalCollection}", $remarks);
                                  // === E-signature Images
                                // $imageEsigPath = 'public/e_signatures/';
                                $imageEsigWithEmpNumberPath = $value->rapidx_user->employee_number;
                                $this->insertEsignatureImageIntoSheet(
                                    $imageEsigWithEmpNumberPath,
                                    "F".$startRowQaApprovalCollection,
                                    50,
                                    50,
                                    $sheet,
                                    'ecr_qa'.$index
                                );
                                $sheet->getStyle("A{$startRowQaApprovalCollection}:I{$startRowQaApprovalCollection}")->applyFromArray([
                                    'alignment' => [
                                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                                        'vertical' => Alignment::VERTICAL_CENTER,
                                    ],
                                ]);
                                $startRowQaApprovalCollection++;
                            }else{
                                if (str_contains($approvalStatus, 'OTRB')) {
                                    $sheet->setCellValue("A{$startRowRequestedByApprovalsCollection}", $division);
                                    $sheet->setCellValue("C{$startRowRequestedByApprovalsCollection}", $ecrApprover);
                                    $sheet->setCellValue("E{$startRowRequestedByApprovalsCollection}",$this->getEcrApprovalStatus($approvalStatus));
                                    $sheet->setCellValue("H{$startRowRequestedByApprovalsCollection}", $approvedDate);
                                    $sheet->setCellValue("I{$startRowOtherApprovalsCollection}", $remarks);
                                    // === Insert e-signature
                                    // $imageEsigPath = 'public/e_signatures/';

                                    $imageEsigWithEmpNumberPath = $value->rapidx_user->employee_number;
                                    $this->insertEsignatureImageIntoSheet(
                                        $imageEsigWithEmpNumberPath,
                                        "F".$startRowRequestedByApprovalsCollection,
                                        50,
                                        50,
                                        $sheet,
                                        'ecr_requestedby'.$index
                                    );
                                    $sheet->getStyle("A{$startRowRequestedByApprovalsCollection}:I{$startRowRequestedByApprovalsCollection}")->applyFromArray([
                                        'alignment' => [
                                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                                            'vertical' => Alignment::VERTICAL_CENTER,
                                        ],
                                    ]);
                                    $startRowRequestedByApprovalsCollection++;
                                }
                                if ( !str_contains($approvalStatus, 'OTRB')) {
                                    $sheet->setCellValue("A{$startRowOtherApprovalsCollection}", $division);
                                    $sheet->setCellValue("C{$startRowOtherApprovalsCollection}", $ecrApprover);
                                    $sheet->setCellValue("E{$startRowOtherApprovalsCollection}",$this->getEcrApprovalStatus($approvalStatus));
                                    $sheet->setCellValue("H{$startRowOtherApprovalsCollection}", $approvedDate);
                                    $sheet->setCellValue("I{$startRowOtherApprovalsCollection}", $remarks);
                                      // === Insert e-signature

                                      $imageEsigWithEmpNumberPath = $value->rapidx_user->employee_number;
                                      $this->insertEsignatureImageIntoSheet(
                                          $imageEsigWithEmpNumberPath,
                                          "F".$startRowOtherApprovalsCollection,
                                          50,
                                          50,
                                          $sheet,
                                          'ecr_engg'.$index
                                      );
                                    $sheet->getStyle("A{$startRowOtherApprovalsCollection}:I{$startRowOtherApprovalsCollection}")->applyFromArray([
                                        'alignment' => [
                                            'horizontal' => Alignment::HORIZONTAL_LEFT,
                                            'vertical' => Alignment::VERTICAL_CENTER,
                                        ],
                                    ]);
                                    $startRowOtherApprovalsCollection++;
                                }
                            }
                        }
                    }
                }

                // 5.  DOCUMENT REVISION
                if(filled($documentDetails)){
                    $startRowDocDetailsCollection = 58;
                    // echo ($documentDetails);
                    // exit;
                    $startColumnDocDetailsCollection = [
                        'A',
                        'E',
                        'F',
                        'I',
                    ];
                    foreach ($documentDetails as $key => $documentDetailsValue) {
                        $sheet->setCellValue("{$startColumnDocDetailsCollection[0]}{$startRowDocDetailsCollection}", $documentDetailsValue['document_number']);
                        $sheet->setCellValue("{$startColumnDocDetailsCollection[1]}{$startRowDocDetailsCollection}", $documentDetailsValue['revision_no']);
                        $sheet->setCellValue("{$startColumnDocDetailsCollection[2]}{$startRowDocDetailsCollection}", $documentDetailsValue['rapidx_user_person_in_charge']['name']);
                        $sheet->setCellValue("{$startColumnDocDetailsCollection[3]}{$startRowDocDetailsCollection}", $documentDetailsValue['revision_due_date']);

                        $sheet->getStyle("A{$startRowDocDetailsCollection}:I{$startRowDocDetailsCollection}")->applyFromArray([
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_LEFT,
                                'vertical' => Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                        $startRowDocDetailsCollection++;
                    }
                }

                // ==== PMI APPROVAL ====
                $internalPbCol = "A";
                $internalPb = $pmiApprovalCollection['PB'] ?? null;
                if(filled($internalPb)){
                    foreach ($internalPb as $key => $internalPbValue) {
                        $this->insertEsignatureImageIntoSheet(
                            $internalPbValue['rapidx_user']['employee_number'],
                            $internalPbCol."69",
                            50,
                            50,
                            $sheet,
                            'pb_head'.$key
                        );

                        $sheet->setCellValue($internalPbCol.'70', $internalPbValue['rapidx_user']['name']);
                        $internalPbCol++; //Adjust the Column
                    }
                }
                $startInternalCbCol = "E";
                $internalCb = $pmiApprovalCollection['CB'] ?? null;
                if(filled($internalCb)){
                    foreach ($internalCb as $key => $internalCbValue) {

                        $this->insertEsignatureImageIntoSheet(
                            $internalCbValue['rapidx_user']['employee_number'],
                            $startInternalCbCol."69",
                            50,
                            50,
                            $sheet,
                            'cb_head'.$key
                        );

                        $sheet->setCellValue($startInternalCbCol.'70', $internalCbValue['rapidx_user']['name']);
                        $startInternalCbCol++; //Adjust the Column
                    }
                }

                $startInternalAbCol = "H";
                $internalAb = $pmiApprovalCollection['AB'] ?? null;
                if(filled($internalAb)){
                    foreach ($internalAb as $key => $internalAbValue) {

                        $this->insertEsignatureImageIntoSheet(
                            $internalAbValue['rapidx_user']['employee_number'],
                            $startInternalAbCol."69",
                            50,
                            50,
                            $sheet,
                            'ab_head'.$key
                        );

                        $sheet->setCellValue($startInternalAbCol.'70', $internalAbValue['rapidx_user']['name']);
                        $startInternalAbCol++; //Adjust the Column
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
                    // 5.  Document Revision
                    "A58:D58",
                    "A59:D59",
                    "A60:D60",
                    "A61:D61",
                    "F58:H58",
                    "F59:H59",
                    "F60:H60",
                    "F61:H61",
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
                    // 10.  PMI APROVAL BY
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
                $allThinBorderMergeRow = [
                    "44",
                    "45",

                    "52",
                    "53",
                    "54",
                    "55",

                    "64",
                    "65",
                    "66",
                ];

                foreach ($allThinBorderMergeRow as $key => $allThinBorderMergeRowValue) {
                    $sheet->mergeCells("A{$allThinBorderMergeRowValue}:B{$allThinBorderMergeRowValue}");
                    $sheet->mergeCells("C{$allThinBorderMergeRowValue}:D{$allThinBorderMergeRowValue}");
                    $sheet->mergeCells("F{$allThinBorderMergeRowValue}:G{$allThinBorderMergeRowValue}");

                    $sheet->getStyle("A{$allThinBorderMergeRowValue}:I{$allThinBorderMergeRowValue}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }

                // === Apply borders to specific cells ===
                $allThinBorder = [
                    "A43:I43",
                    "C47",
                    "G47",
                    // 6 SECTION
                    "A51:I51",
                    'A57:I57',
                    //8 9 10 SECTION
                    'A63:I63',
                    'C75',
                    'G75',
                    'C82',
                    'G82',
                    //=== 7. DOCUMENT REVISION
                    "A58:I61",
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
                    "A4:A8",
                    "E4:E8",
                    "F4:F8",

                ];
                $bottomThinBorder = [
                    // "A4:I4",
                    "A70",
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
                    'B' => 13,
                    'C' => 6,
                    'D' => 35,
                    'E' => 35,
                    'F' => 35,
                    'G' => 6,
                    'H' => 30,
                    'I' => 30,
                    'J' => 20,
                ];
                foreach ($columnWidths as $col => $width) {
                    $sheet->getColumnDimension($col)->setWidth($width);
                }


            },
        ];
    }
}
