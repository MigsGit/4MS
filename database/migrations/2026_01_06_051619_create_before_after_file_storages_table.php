<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeforeAfterFileStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('before_after_file_storages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ecrs_id')->references('id')->on('ecrs');
            $table->longText('filtered_document_name_before')->nullable();
            $table->longText('original_filename_before')->nullable();
            $table->longText('filtered_document_name_after')->nullable();
            $table->longText('original_filename_after')->nullable();
            $table->string('file_path')->default('method');
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
        Schema::dropIfExists('before_afeter_file_storages');
    }
}
