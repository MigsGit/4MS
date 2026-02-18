<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManDetailsTable extends Migration
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
            $table->longText('filtered_document_name')->nullable();
            $table->longText('original_filename')->nullable();
            $table->string('status')->default('RUP')->comment('RUP - For Requestor Update');
            $table->string('approval_status')->default('RUP')->comment('RUP - For Requestor Update');
            $table->foreignId('ecrs_id')->references('id')->on('ecrs')->comment ='Ecr Id';
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('man_details');
    }
}
