<?php

namespace App\Interfaces;

interface CommonInterface
{

    public function uploadFile($txtDocuReference,$id,$path);
    public function viewPdfFile($pdfPath);
}
