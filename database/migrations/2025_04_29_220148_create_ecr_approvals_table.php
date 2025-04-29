<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcrApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecr_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecrs_id')->references('id')->on('ecrs')->comment ='Ecr Id';
            //manually inject relationship in MYSQL relation view
            $table->unsignedBigInteger('rapidx_user_id')->unique()->comment('Rapidx User Id');
            //MYSQL restrict this function if different database
            // $table->foreignId('rapidx_users_id')->references('id')->on('rapidx.users')->comment ='Rapidx User Id';
            $table->string('status')->default('PE')->comment('PEN-Pending | APP-Approved | DIS-Disapproved');
            $table->string('type')->default('RB');
            $table->bigInteger('counter');
            $table->longText('remarks');
            $table->timestamps();
            $table->softDeletes();
            /*
                //ECR Detail
                description_of_change[]: 1
                reason_of_change[]: 3
                //ECR Other Approver
                requested_by[]: 149
                technical_evaluation[]: 564
                reviewed_by[]: 121
                //ECR QA Approver
                qad_approved_by_external: 263
                qad_approved_by_internal: 14
                qad_checked_by: 121
                //PMI Approver
                prepared_by[]: 833
                checked_by[]: 833
                approved_by[]: 689
            */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ecr_approvals');
    }
}
