<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('man', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecrs_id')->references('id')->on('ecrs')->comment ='Ecr Id';
            $table->foreignId('reason_of_change')->references('id')->on('dropdown_master_details')->comment ='EDropdownMasterDetails Id';
            $table->unsignedBigInteger('rapidx_user_id')->unique()->comment('Rapidx User Id');
            $table->longText('type_of_part')->nullable();
            $table->date('change_imp_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            /*
            first_assign //s
            long_interval //s
            change //s
            process_name //l
            working_time time
            trainer //big

            qc_inspector_operator //big
            trainer_sample_size //int
            trainer_result //s

            lqc_supervisor //big
            lqc_sample_size //int
            lqc_result //s

            process_change_factor //l
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
        Schema::dropIfExists('man');
    }
}
