<?php

namespace App\Interfaces;

interface CommonInterface
{

    public function uploadFile($txtDocuReference,$id,$path);
    public function uploadFileImg($machineRefBefore,$machineRefAfter,$id,$path);
    public function viewPdfFile($pdfPath);
    public function viewImageFile($filePath);
    public function getPmiApprovalStatus($approvalStatus);
}
