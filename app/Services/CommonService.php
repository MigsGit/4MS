<?php
namespace App\Services;
use setasign\Fpdi\Fpdi;
use App\Interfaces\FileInterface;
use App\Interfaces\CommonInterface;
use Illuminate\Support\Facades\Storage;


class CommonService implements CommonInterface
{
    protected $fileInterface;
    protected $fpdi;
    public function __construct(FileInterface $fileInterface,Fpdi $fpdi) {
        $this->fileInterface = $fileInterface;
        $this->fpdi = $fpdi;
    }

    public function uploadFile($txtDocuReference,$id,$path) // $request->txt_docu_reference
    {
        try {
            $currentPath= 'public/'.$path.'/'.$id;
            $newFolderPath= 'public/'.$path.'/'.$id.'_'.time();
            if (Storage::exists($currentPath)) {
                Storage::deleteDirectory($currentPath);
                // Storage::move($currentPath, $newFolderPath); //change file name if exist
            }
            $arr_filtered_filename = [];
            $arr_original_filename = [];
            foreach ($txtDocuReference as $key => $file) { //$request->file('txt_docu_reference')
                $original_filename = $file->getClientOriginalName(); //'/etc#hosts/@Álix Ãxel likes - beer?!.pdf';
                $filtered_filename = $key.'_'.$this->fileInterface->Slug($original_filename, '_', '.');	 // _etc_hosts_alix_axel_likes_beer.pdf //Interface

                // $file->storeAs($folderPath, $filtered_filename, 'public'); // 'storage' disk is used for storing files // not active
                Storage::putFileAs($currentPath, $file, $filtered_filename);//change file to storage //active
                $arr_original_filename[] =$original_filename;
                $arr_filtered_filename[] =$filtered_filename;
            }
            return [
                'arr_filtered_document_name' => $arr_filtered_filename,
                'arr_original_filename' => $arr_original_filename,
            ];
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function viewPdfFile($pdfPath){

        try {
            $pageCount = $this->fpdi->setSourceFile($pdfPath);
            //Read all page using page count
            for ($i=1; $i <= $pageCount; $i++) {
                $templateId = $this->fpdi->importPage($i);
                // Insert the image at specified coordinates
                $pdfDimensions = $this->fpdi->getTemplateSize($templateId);
                $w = $pdfDimensions['width'];
                $h = $pdfDimensions['height'];
                //Calculate the position of the Signature Image

                $orientation 	= 'P';
                $page_size 	= 'A4';
                if($w > $h){
                    $orientation 	= 'L';
                    /* A4 size is width 210 x height 297 mm */
                    /* A3 size is width 297 x height 420 mm */
                    if($w > 297){
                        $page_size 			= 'A3';
                    }
                }

                // Add a page to the PDF
                $this->fpdi->AddPage($orientation,$page_size);
                $this->fpdi->useTemplate($templateId);

                // Add a image to the PDF
                $this->fpdi->SetFont('Arial', '', 5);

                // Generate a file path for the output PDF
                // $outputPath = storage_path('app/public/modified_pdf.pdf');
            }
            $this->fpdi->Output();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
