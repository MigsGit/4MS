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
