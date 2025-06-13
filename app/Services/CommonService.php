<?php
namespace App\Services;
use Helpers;

use App\Interfaces\FileInterface;
use App\Interfaces\CommonInterface;
use Illuminate\Support\Facades\Storage;


class CommonService implements CommonInterface
{
    protected $fileInterface;
    public function __construct(FileInterface $fileInterface) {
        $this->fileInterface = $fileInterface;
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
}
