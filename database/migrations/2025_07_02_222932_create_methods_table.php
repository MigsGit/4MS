<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('methods', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('RUP')->comment('RUP - For Requestor Update');
            $table->string('approval_status')->default('RUP')->comment('RUP - For Requestor Update');
            $table->foreignId('ecrs_id')->references('id')->on('ecrs')->comment ='Ecr Id';
            $table->foreignId('before_after_file_storages_id')->references('id')->on('before_after_file_storages')->comment ='beforeAfterFileStorages';
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
        Schema::dropIfExists('methods');
    }
}
