<?php
namespace App\Services;
use App\Models\Ecr;
use App\Models\RapidxUser;
use App\Models\RapidMailer;
use App\Models\RapidAutoMailer;
use App\Interfaces\FileInterface;
use App\Interfaces\EmailInterface;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;


class EmailService implements EmailInterface
{

    protected $commonInterface;
    public function __construct(CommonInterface $commonInterface) {
        $this->commonInterface = $commonInterface;
    }
    public function getEmailByRapidxUserId($userId){
        try {
            $user = RapidxUser::find($userId);
            if (!$user) {
                throw new \Exception('User not found');
            }
            if (!$user->email) {
                throw new \Exception('User Email not found');
            }
           return [
            'fullName' => $user->name,
            'email' => $user->email,
        ];
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function sendEmail($data){
        try {
            date_default_timezone_set('Asia/Manila');
            DB::beginTransaction();
            return RapidMailer::insert($data);
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function sendEmailWithAttachment($data){
        try {
            date_default_timezone_set('Asia/Manila');
            DB::beginTransaction();
            return RapidMailer::insert($data);
            DB::commit();
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function sendEmailWithSchedule($data){
        try {
            date_default_timezone_set('Asia/Manila');
            DB::beginTransaction();
            return RapidAutoMailer::insert($data);
            DB::commit();

            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            throw $e;
        }
    }
    public function ecrEmailMsg($ecrsId){
        $ecr = Ecr::with('rapidx_user_created_by')->find($ecrsId);
        $approvalStatus = $this->commonInterface->getEcrApprovalStatus($ecr->approval_status);
        $createdBy = $ecr->rapidx_user_created_by->name;
        $getEcrStatus = $this->commonInterface->getEcrStatus($ecr->status);
        $getEcrStatus['status'];
        if($getEcrStatus['status'] == 'DISAPPROVED'){
            $header = "Your ECR has been disapproved";
        }else if($getEcrStatus['status'] == 'APPROVED'){
            $header = "Your ECR has been approved";
        }else{
            $header = "Please see the ECR for your approval.";
        }
        return $msg = '<!DOCTYPE html>
            <html>
                <head>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
                    <style type="text/css">
                        body{
                            font-family: Arial;
                            font-size: 15px;
                        }
                        .text-green{
                            color: green;
                            font-weight: bold;
                        }
                    </style>
                </head>
                <body>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row" style="margin: 1px 10px;">
                                <div class="col-sm-12">
                                    <form id="frmSaveRecord">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label style="font-size: 18px;">Good day!</label><br>
                                                <label style="font-size: 18px;">'.$header.'</label>
                                                <br>
                                                <hr>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b>Ecr Control No. : </b><span class="text-black"> '. $ecr->ecr_no.' </span></label>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="col-sm-12">
                                                <div   div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b>Approval Status: </b> '. $approvalStatus['approvalStatus'].' </label>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b>Category : </b><span class="text-black"> '.$ecr->category.'</span></label>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b>Customer Name: </b><span class="text-black"> '.$ecr->customer_name.' </span></label>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b>Part Number : </b><span class="text-black"> '.$ecr->part_no.' </span></label>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b>Part Name : </b><span class="text-black"> '.$ecr->part_name.' </span></label>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b> Device Name : </b><span class="text-black"> '.$ecr->device_name.' </span></label>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b> Product Line : </b><span class="text-black"> '.$ecr->product_line.' </span></label>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b> Section : </b><span class="text-black"> '.$ecr->section.' </span></label>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b> Customer Ec #: </b><span class="text-black"> '.$ecr->customer_ec_no.' </span></label>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b> Date Of Request: </b><span class="text-black"> '.$ecr->date_of_request.' </span></label>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b> Requested By: </b><span class="text-black"> '.$createdBy.'</span></label>
                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label">For more info, please log-in to your Rapidx account. Go to http://rapidx/ and Click http://rapidx/4M/dashboard </label>
                                                </div>
                                            </div>

                                            <br>
                                            <br>

                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b> Notice of Disclaimer: </b></label>
                                                    <br>
                                                    <label class="col-sm-12 col-form-label"></label>   This message contains confidential information intended for a specific individual and purpose. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.</label>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <br><br>
                                                <label style="font-size: 18px;"><b>For concerns on using the form, please contact ISS at local numbers 205, 206, or 208. You may send us e-mail at <a href="mailto: servicerequest@pricon.ph">servicerequest@pricon.ph</a></b></label>
                                            </div>
                                        </div>

                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </body>
            </html>';
    }
    public function ecrEmailMsgWithStatus($ecrsId){
        $ecr = Ecr::with('rapidx_user_created_by')->find($ecrsId);
        $approvalStatus = $this->commonInterface->getEcrApprovalStatus($ecr->approval_status);
        $createdBy = $ecr->rapidx_user_created_by->name;
        return $msg = '<!DOCTYPE html>
            <html>
                <head>
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
                    <style type="text/css">
                        body{
                            font-family: Arial;
                            font-size: 15px;
                        }
                        .text-green{
                            color: green;
                            font-weight: bold;
                        }
                    </style>
                </head>
                <body>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row" style="margin: 1px 10px;">
                                <div class="col-sm-12">
                                    <form id="frmSaveRecord">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <label style="font-size: 18px;">Good day!</label><br>
                                                <label style="font-size: 18px;">Please see the ECR for your approval.</label>
                                                <br>
                                                <hr>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b>Ecr Control No. : </b><span class="text-black"> '. $ecr->ecr_no.' </span></label>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="col-sm-12">
                                                <div   div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b>Approval Status: </b> '. $approvalStatus['approvalStatus'].' </label>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b>Category : </b><span class="text-black"> '.$ecr->category.'</span></label>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b>Customer Name: </b><span class="text-black"> '.$ecr->customer_name.' </span></label>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b>Part Number : </b><span class="text-black"> '.$ecr->part_no.' </span></label>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b>Part Name : </b><span class="text-black"> '.$ecr->part_name.' </span></label>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b> Device Name : </b><span class="text-black"> '.$ecr->device_name.' </span></label>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b> Product Line : </b><span class="text-black"> '.$ecr->product_line.' </span></label>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b> Section : </b><span class="text-black"> '.$ecr->section.' </span></label>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b> Customer Ec #: </b><span class="text-black"> '.$ecr->customer_ec_no.' </span></label>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b> Date Of Request: </b><span class="text-black"> '.$ecr->date_of_request.' </span></label>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b> Requested By: </b><span class="text-black"> '.$createdBy.'</span></label>
                                                </div>
                                            </div>
                                            <br>
                                            <br>
                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label">For more info, please log-in to your Rapidx account. Go to http://rapidx/ and Click http://rapidx/4M/dashboard </label>
                                                </div>
                                            </div>

                                            <br>
                                            <br>

                                            <div class="col-sm-12">
                                                <div class="form-group row">
                                                    <label class="col-sm-12 col-form-label"><b> Notice of Disclaimer: </b></label>
                                                    <br>
                                                    <label class="col-sm-12 col-form-label"></label>   This message contains confidential information intended for a specific individual and purpose. If you are not the intended recipient, you should delete this message. Any disclosure,copying, or distribution of this message, or the taking of any action based on it, is strictly prohibited.</label>
                                                </div>
                                            </div>

                                            <div class="col-sm-12">
                                                <br><br>
                                                <label style="font-size: 18px;"><b>For concerns on using the form, please contact ISS at local numbers 205, 206, or 208. You may send us e-mail at <a href="mailto: servicerequest@pricon.ph">servicerequest@pricon.ph</a></b></label>
                                            </div>
                                        </div>

                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </body>
            </html>';
    }
}
